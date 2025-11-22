<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Models\Node;
use Pterodactyl\Models\Allocation;
use Pterodactyl\Http\Controllers\Controller;
use Prologue\Alerts\AlertsMessageBag;
use Pterodactyl\Services\Nodes\NodeUpdateService;
use Pterodactyl\Services\Nodes\NodeCreationService;
use Pterodactyl\Services\Nodes\NodeDeletionService;
use Pterodactyl\Services\Allocations\AssignmentService;
use Pterodactyl\Services\Allocations\AllocationDeletionService;
use Pterodactyl\Contracts\Repository\NodeRepositoryInterface;
use Pterodactyl\Contracts\Repository\ServerRepositoryInterface;
use Pterodactyl\Contracts\Repository\LocationRepositoryInterface;
use Pterodactyl\Contracts\Repository\AllocationRepositoryInterface;
use Pterodactyl\Http\Requests\Admin\Node\NodeFormRequest;
use Pterodactyl\Http\Requests\Admin\Node\AllocationFormRequest;
use Pterodactyl\Http\Requests\Admin\Node\AllocationAliasFormRequest;
use Pterodactyl\Services\Helpers\SoftwareVersionService;

class NodesController extends Controller
{
    public function __construct(
        protected AlertsMessageBag $alert,
        protected AllocationDeletionService $allocationDeletionService,
        protected AllocationRepositoryInterface $allocationRepository,
        protected AssignmentService $assignmentService,
        protected NodeCreationService $creationService,
        protected NodeDeletionService $deletionService,
        protected LocationRepositoryInterface $locationRepository,
        protected NodeRepositoryInterface $repository,
        protected ServerRepositoryInterface $serverRepository,
        protected NodeUpdateService $updateService,
        protected SoftwareVersionService $versionService,
        protected ViewFactory $view
    ) {}

    /**
     * Membuat node baru.
     */
    public function create(): View|RedirectResponse
    {
        $locations = $this->locationRepository->all();
        if (count($locations) < 1) {
            $this->alert->warning(trans('admin/node.notices.location_required'))->flash();
            return redirect()->route('admin.locations');
        }

        return $this->view->make('admin.nodes.new', ['locations' => $locations]);
    }

    /**
     * Simpan node baru.
     */
    public function store(NodeFormRequest $request): RedirectResponse
    {
        $user = auth()->user();
        if ($user->id !== 1) {
            abort(403, "🚫 Kamu tidak punya izin untuk menambahkan node. Hanya admin ID 1 yang bisa!");
        }

        $node = $this->creationService->handle($request->normalize());
        $this->alert->info(trans('admin/node.notices.node_created'))->flash();
        return redirect()->route('admin.nodes.view.allocation', $node->id);
    }

    /**
     * Update node (khusus Admin ID 1).
     */
    public function updateSettings(NodeFormRequest $request, Node $node): RedirectResponse
    {
        $user = auth()->user();
        if ($user->id !== 1) {
            abort(403, "⚠️ AKSES DI TOLAK HANYA ADMIN ID 1 YANG BISA EDIT NODE");
        }

        $this->updateService->handle($node, $request->normalize(), $request->input('reset_secret') === 'on');
        $this->alert->success(trans('admin/node.notices.node_updated'))->flash();
        return redirect()->route('admin.nodes.view.settings', $node->id)->withInput();
    }

    /**
     * Hapus node (khusus Admin ID 1).
     */
    public function delete(int|Node $node): RedirectResponse
    {
        $user = auth()->user();
        if ($user->id !== 1) {
            abort(403, "❌ 𝐋𝐔 𝐒𝐄𝐇𝐀𝐓 𝐍𝐆𝐄𝐋𝐀𝐊𝐔𝐈𝐍 𝐇𝐀𝐏𝐔𝐒 𝐍𝐎𝐃𝐄?");
        }

        $this->deletionService->handle($node);
        $this->alert->success(trans('admin/node.notices.node_deleted'))->flash();
        return redirect()->route('admin.nodes');
    }

    /**
     * Create new allocations for a node.
     */
    public function createAllocation(AllocationFormRequest $request, Node $node): RedirectResponse
    {
        try {
            $this->assignmentService->handle($node, $request->validated());
            $this->alert->success(trans('admin/node.notices.allocation_created'))->flash();
        } catch (\Exception $e) {
            $this->alert->danger('Gagal menambahkan allocation: ' . $e->getMessage())->flash();
        }

        return redirect()->route('admin.nodes.view.allocation', $node->id);
    }

    /**
     * Remove a block of allocations from a node.
     */
    public function allocationRemoveBlock(Request $request, Node $node): RedirectResponse
    {
        $data = $request->validate([
            'allocation_ip' => 'required|string',
            'allocation_ports' => 'required|array',
        ]);

        $allocations = Allocation::where('node_id', $node->id)
            ->where('ip', $data['allocation_ip'])
            ->whereIn('port', $data['allocation_ports'])
            ->get();

        foreach ($allocations as $allocation) {
            $this->allocationDeletionService->handle($allocation);
        }

        $this->alert->success(trans('admin/node.notices.allocations_removed'))->flash();
        return redirect()->route('admin.nodes.view.allocation', $node->id);
    }

    /**
     * Set an alias for a specific allocation.
     */
    public function allocationSetAlias(AllocationAliasFormRequest $request, Node $node): RedirectResponse
    {
        $allocation = $this->allocationRepository->findFirstWhere([
            ['node_id', '=', $node->id],
            ['ip', '=', $request->input('allocation_ip')],
            ['port', '=', $request->input('allocation_port')],
        ]);

        $allocation->update(['ip_alias' => $request->input('allocation_alias')]);

        $this->alert->success(trans('admin/node.notices.alias_updated'))->flash();
        return redirect()->route('admin.nodes.view.allocation', $node->id);
    }

    /**
     * Remove a single allocation from a node.
     */
    public function allocationRemoveSingle(Node $node, Allocation $allocation): RedirectResponse
    {
        if ($allocation->node_id !== $node->id) {
            abort(404);
        }

        $this->allocationDeletionService->handle($allocation);

        $this->alert->success(trans('admin/node.notices.allocation_removed'))->flash();
        return redirect()->route('admin.nodes.view.allocation', $node->id);
    }

    /**
     * Remove multiple allocations from a node.
     */
    public function allocationRemoveMultiple(Request $request, Node $node): RedirectResponse
    {
        $data = $request->validate([
            'allocations' => 'required|array',
            'allocations.*' => 'integer',
        ]);

        $allocations = Allocation::where('node_id', $node->id)
            ->whereIn('id', $data['allocations'])
            ->get();

        foreach ($allocations as $allocation) {
            $this->allocationDeletionService->handle($allocation);
        }

        $this->alert->success(trans('admin/node.notices.allocations_removed'))->flash();
        return redirect()->route('admin.nodes.view.allocation', $node->id);
    }
}
