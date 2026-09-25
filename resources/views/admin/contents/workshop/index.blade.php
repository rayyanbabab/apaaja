@extends('admin.layouts.dashboard')

@section('title', 'Denah Interaktif Bengkel (2D Digital Twin)')

@section('content')
<style>

[x-cloak] { display: none !important; }

html.dark .ws-card   { background-color: #0f172a !important; border-color: #1e293b !important; }
html.dark .ws-title  { color: #f8fafc !important; }
html.dark .ws-sub    { color: #94a3b8 !important; }
html.dark .ws-canvas-bg {
    background-color: #090d16 !important;
    background-image:
        linear-gradient(rgba(30, 41, 59, 0.45) 1px, transparent 1px),
        linear-gradient(90deg, rgba(30, 41, 59, 0.45) 1px, transparent 1px),
        linear-gradient(rgba(51, 65, 85, 0.2) 1px, transparent 1px),
        linear-gradient(90deg, rgba(51, 65, 85, 0.2) 1px, transparent 1px);
    background-size: 100px 100px, 100px 100px, 20px 20px, 20px 20px;
    height: 720px !important;
    min-height: 720px !important;
}
.ws-canvas-light {
    height: 720px !important;
    min-height: 720px !important;
    background-color: #f8fafc;
    background-image:
        linear-gradient(rgba(203, 213, 225, 0.65) 1px, transparent 1px),
        linear-gradient(90deg, rgba(203, 213, 225, 0.65) 1px, transparent 1px),
        linear-gradient(rgba(226, 232, 240, 0.45) 1px, transparent 1px),
        linear-gradient(90deg, rgba(226, 232, 240, 0.45) 1px, transparent 1px);
    background-size: 100px 100px, 100px 100px, 20px 20px, 20px 20px;
}

@keyframes radar-ping {
    0% { transform: scale(0.9); opacity: 1; }
    50% { transform: scale(1.35); opacity: 0.6; }
    100% { transform: scale(1.6); opacity: 0; }
}
.animate-radar {
    animation: radar-ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
}

@keyframes beacon-pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(1.2); }
}
.animate-beacon {
    animation: beacon-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.zone-machining, .zone-color-blue     { border: 2px dashed rgba(59, 130, 246, 0.45); background: rgba(59, 130, 246, 0.05); }
.zone-tool_crib, .zone-color-emerald  { border: 2px dashed rgba(16, 185, 129, 0.45); background: rgba(16, 185, 129, 0.05); }
.zone-assembly,  .zone-color-cyan     { border: 2px dashed rgba(6, 182, 212, 0.45);  background: rgba(6, 182, 212, 0.05); }
.zone-safety,    .zone-color-rose     { border: 2px dashed rgba(244, 63, 94, 0.45);  background: rgba(244, 63, 94, 0.05); }
.zone-logistics, .zone-color-slate    { border: 2px dashed rgba(100, 116, 139, 0.45); background: rgba(100, 116, 139, 0.05); }
.zone-color-amber                     { border: 2px dashed rgba(245, 158, 11, 0.45);  background: rgba(245, 158, 11, 0.05); }
.zone-color-purple                    { border: 2px dashed rgba(168, 85, 247, 0.45);  background: rgba(168, 85, 247, 0.05); }
.zone-color-indigo                    { border: 2px dashed rgba(99, 102, 241, 0.45);  background: rgba(99, 102, 241, 0.05); }

.ws-node {
    user-select: none;
    transition: transform 0.15s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.15s ease, border-color 0.15s ease;
}
.ws-node:hover {
    transform: translateY(-2px);
    z-index: 30;
}
.ws-node.is-dragging {
    opacity: 0.9;
    z-index: 50 !important;
    cursor: grabbing !important;
    box-shadow: 0 25px 30px -5px rgba(0, 0, 0, 0.35), 0 10px 10px -5px rgba(0, 0, 0, 0.2) !important;
}

.ws-zone {
    user-select: none;
    transition: box-shadow 0.15s ease, border-color 0.15s ease;
}
.ws-zone.is-dragging {
    opacity: 0.95;
    z-index: 25 !important;
    cursor: grabbing !important;
    box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.3), 0 8px 10px -6px rgba(99, 102, 241, 0.2) !important;
}
.ws-zone.is-resizing {
    z-index: 25 !important;
    box-shadow: 0 20px 25px -5px rgba(245, 158, 11, 0.3), 0 8px 10px -6px rgba(245, 158, 11, 0.2) !important;
}

.ws-drawer-overlay,
.ws-modal-overlay {
    z-index: 99999 !important;
}
</style>

<script>
function workshopDigitalTwin(initialNodes, initialSummary, initialZones) {
    return {
        nodes: initialNodes || [],
        summary: initialSummary || {},
        zones: initialZones || [],
        selectedNode: null,
        drawerOpen: false,
        layerMode: 'all',
        searchQuery: '',
        isLoading: false,
        isEditorMode: false,
        hasUnsavedPositions: false,
        isSaving: false,
        isFullscreen: false,

        zoomLevel: 1.0,
        panOffset: { x: 0, y: 0 },
        isPanning: false,
        panStart: { x: 0, y: 0 },

        isDraggingNodeId: null,
        dragStartPos: { x: 0, y: 0 },
        dragInitialNodePos: { x: 0, y: 0 },

        isDraggingZoneId: null,
        dragInitialZonePos: { x: 0, y: 0 },
        isResizingZoneId: null,
        dragInitialZoneSize: { width: 0, height: 0 },

        nodeModal: {
            open: false,
            isEdit: false,
            showCoords: false,
            data: {
                id: null,
                name: '',
                code: '',
                type: 'rack',
                zone: 'tool_crib',
                location_id: '',
                sizePreset: 'medium',
                pos_x: 15,
                pos_y: 15,
                width: 18,
                height: 16,
                icon: 'rack',
                color_theme: 'blue',
                description: '',
            }
        },

        deleteModal: {
            open: false,
            nodeId: null,
            nodeName: '',
        },

        initComponent() {
            setInterval(() => {
                if (!this.isEditorMode && !this.drawerOpen && !this.nodeModal.open) {
                    this.fetchLiveData();
                }
            }, 12000);
        },

        async fetchLiveData() {
            this.isLoading = true;
            try {
                const response = await fetch('{{ route('admin.workshop.api-data') }}');
                const result = await response.json();
                if (result.success) {
                    if (!this.hasUnsavedPositions) {
                        this.nodes = result.nodes;
                        if (result.zones) {
                            this.zones = result.zones;
                        }
                    } else {
                        this.nodes = this.nodes.map(n => {
                            const updated = result.nodes.find(un => un.id === n.id);
                            if (updated) {
                                n.telemetry = updated.telemetry;
                            }
                            return n;
                        });
                    }
                    this.summary = result.summary;

                    if (this.selectedNode) {
                        const updatedSelected = this.nodes.find(n => n.id === this.selectedNode.id);
                        if (updatedSelected) {
                            this.selectedNode = updatedSelected;
                        }
                    }
                }
            } catch (err) {
                console.error('Failed to sync live workshop telemetry:', err);
            } finally {
                this.isLoading = false;
            }
        },

        get filteredNodes() {
            return this.nodes.filter(node => {
                if (this.layerMode === 'availability') {
                    return true;
                } else if (this.layerMode === 'maintenance') {
                    return (node.telemetry?.in_maintenance_count > 0 || node.telemetry?.damaged_items_count > 0 || node.telemetry?.status === 'maintenance');
                } else if (this.layerMode === 'safety') {
                    return (node.zone === 'safety' || node.telemetry?.high_risk_count > 0 || node.type === 'safety_kiosk');
                } else if (this.layerMode === 'logistics') {
                    return (node.telemetry?.low_stock_count > 0 || node.zone === 'logistics');
                } else if (this.layerMode === 'calibration') {
                    return (node.telemetry?.expired_calibration_count > 0 || node.telemetry?.due_calibration_count > 0 || node.icon === 'micrometer');
                }
                return true;
            });
        },

        setLayerMode(mode) {
            this.layerMode = mode;
        },

        isSpotlightNode(node) {
            if (!this.searchQuery.trim()) return false;
            const q = this.searchQuery.toLowerCase();
            const matchName = (node.name || '').toLowerCase().includes(q);
            const matchCode = (node.code || '').toLowerCase().includes(q);
            const matchItems = (node.telemetry?.items || []).some(it =>
                (it.nama || '').toLowerCase().includes(q) || (it.kode || '').toLowerCase().includes(q)
            );
            return matchName || matchCode || matchItems;
        },

        clearSearch() {
            this.searchQuery = '';
        },

        zoomIn() {
            if (this.zoomLevel < 3.5) this.zoomLevel = +(this.zoomLevel + 0.15).toFixed(2);
        },
        zoomOut() {
            if (this.zoomLevel > 0.25) this.zoomLevel = +(this.zoomLevel - 0.15).toFixed(2);
        },
        resetZoom() {
            this.zoomLevel = 1.0;
            this.panOffset = { x: 0, y: 0 };
        },
        getClientCoords(e) {
            if (e.touches && e.touches.length > 0) {
                return { x: e.touches[0].clientX, y: e.touches[0].clientY };
            }
            if (e.changedTouches && e.changedTouches.length > 0) {
                return { x: e.changedTouches[0].clientX, y: e.changedTouches[0].clientY };
            }
            return { x: e.clientX, y: e.clientY };
        },
        onWheel(e) {
            if (e.deltaY < 0) this.zoomIn(); else this.zoomOut();
        },
        startPan(e) {
            if (e.target.closest('.ws-node') || (this.isEditorMode && e.target.closest('.ws-zone')) || e.target.closest('button')) return;
            const pt = this.getClientCoords(e);
            this.isPanning = true;
            this.panStart = { x: pt.x - this.panOffset.x, y: pt.y - this.panOffset.y };
        },
        onPan(e) {
            const pt = this.getClientCoords(e);
            if (this.isPanning) {
                this.panOffset = { x: pt.x - this.panStart.x, y: pt.y - this.panStart.y };
            } else if (this.isDraggingNodeId && this.isEditorMode) {
                if (e.cancelable) e.preventDefault();
                const node = this.nodes.find(n => n.id === this.isDraggingNodeId);
                if (node) {
                    const canvas = e.currentTarget;
                    const rect = canvas.getBoundingClientRect();
                    const deltaX = (pt.x - this.dragStartPos.x) / (rect.width * this.zoomLevel) * 100;
                    const deltaY = (pt.y - this.dragStartPos.y) / (rect.height * this.zoomLevel) * 100;

                    let newX = Math.round((this.dragInitialNodePos.x + deltaX) * 2) / 2;
                    let newY = Math.round((this.dragInitialNodePos.y + deltaY) * 2) / 2;

                    node.pos_x = Math.max(0, Math.min(200, newX));
                    node.pos_y = Math.max(0, Math.min(200, newY));
                    this.hasUnsavedPositions = true;
                }
            } else if (this.isDraggingZoneId && this.isEditorMode) {
                if (e.cancelable) e.preventDefault();
                const zone = this.zones.find(z => z.id === this.isDraggingZoneId);
                if (zone) {
                    const canvas = e.currentTarget;
                    const rect = canvas.getBoundingClientRect();
                    const deltaX = (pt.x - this.dragStartPos.x) / (rect.width * this.zoomLevel) * 100;
                    const deltaY = (pt.y - this.dragStartPos.y) / (rect.height * this.zoomLevel) * 100;

                    let newX = Math.round((this.dragInitialZonePos.x + deltaX) * 2) / 2;
                    let newY = Math.round((this.dragInitialZonePos.y + deltaY) * 2) / 2;

                    zone.pos_x = Math.max(0, Math.min(200, newX));
                    zone.pos_y = Math.max(0, Math.min(200, newY));
                    this.hasUnsavedPositions = true;
                }
            } else if (this.isResizingZoneId && this.isEditorMode) {
                if (e.cancelable) e.preventDefault();
                const zone = this.zones.find(z => z.id === this.isResizingZoneId);
                if (zone) {
                    const canvas = e.currentTarget;
                    const rect = canvas.getBoundingClientRect();
                    const deltaW = (pt.x - this.dragStartPos.x) / (rect.width * this.zoomLevel) * 100;
                    const deltaH = (pt.y - this.dragStartPos.y) / (rect.height * this.zoomLevel) * 100;

                    let newW = Math.round((this.dragInitialZoneSize.width + deltaW) * 2) / 2;
                    let newH = Math.round((this.dragInitialZoneSize.height + deltaH) * 2) / 2;

                    zone.width = Math.max(3, Math.min(200, newW));
                    zone.height = Math.max(3, Math.min(200, newH));
                    this.hasUnsavedPositions = true;
                }
            }
        },
        endPan() {
            this.isPanning = false;
            this.isDraggingNodeId = null;
            this.isDraggingZoneId = null;
            this.isResizingZoneId = null;
        },

        onNodeClick(node, e) {
            if (this.isEditorMode) return;
            this.selectedNode = node;
            this.drawerOpen = true;
        },

        onNodeMouseDown(node, e) {
            if (!this.isEditorMode) return;
            e.stopPropagation();
            const pt = this.getClientCoords(e);
            this.isDraggingNodeId = node.id;
            this.dragStartPos = { x: pt.x, y: pt.y };
            this.dragInitialNodePos = { x: parseFloat(node.pos_x) || 0, y: parseFloat(node.pos_y) || 0 };
        },

        onZoneMouseDown(zone, e) {
            if (!this.isEditorMode) return;
            if (e.target.closest('button') || e.target.closest('.ws-node')) return;
            e.stopPropagation();
            const pt = this.getClientCoords(e);
            this.isDraggingZoneId = zone.id;
            this.dragStartPos = { x: pt.x, y: pt.y };
            this.dragInitialZonePos = { x: parseFloat(zone.pos_x) || 0, y: parseFloat(zone.pos_y) || 0 };
        },

        onZoneResizeMouseDown(zone, e) {
            if (!this.isEditorMode) return;
            e.stopPropagation();
            const pt = this.getClientCoords(e);
            this.isResizingZoneId = zone.id;
            this.dragStartPos = { x: pt.x, y: pt.y };
            this.dragInitialZoneSize = { width: parseFloat(zone.width) || 20, height: parseFloat(zone.height) || 20 };
        },

        closeDrawer() {
            this.drawerOpen = false;
        },

        toggleEditorMode() {
            this.isEditorMode = !this.isEditorMode;
            if (!this.isEditorMode && this.hasUnsavedPositions) {
                if (confirm('Simpan posisi koordinat yang baru Anda geser?')) {
                    this.savePositions();
                }
            }
        },

        async savePositions() {
            this.isSaving = true;
            try {
                const nodePayload = {
                    _token: '{{ csrf_token() }}',
                    nodes: this.nodes.map(n => ({
                        id: n.id,
                        pos_x: n.pos_x,
                        pos_y: n.pos_y,
                        width: n.width,
                        height: n.height,
                    }))
                };

                const zonePayload = {
                    _token: '{{ csrf_token() }}',
                    zones: this.zones.map(z => ({
                        id: z.id,
                        pos_x: z.pos_x,
                        pos_y: z.pos_y,
                        width: z.width,
                        height: z.height,
                    }))
                };

                await Promise.all([
                    fetch('{{ route('admin.workshop.update-positions') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(nodePayload)
                    }),
                    fetch('{{ route('admin.workshop.zones.update-positions') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(zonePayload)
                    })
                ]);

                this.hasUnsavedPositions = false;
                alert('Tata letak stasiun & zona berhasil disimpan!');
            } catch (err) {
                console.error(err);
                alert('Gagal menyimpan posisi denah.');
            } finally {
                this.isSaving = false;
            }
        },

        cancelPositionChanges() {
            this.fetchLiveData();
            this.hasUnsavedPositions = false;
        },

        toggleFullscreen() {
            this.isFullscreen = !this.isFullscreen;
        },

        openAddNodeModal() {
            this.nodeModal = {
                open: true,
                isEdit: false,
                showCoords: false,
                data: {
                    id: null,
                    name: '',
                    code: 'MCH-0' + (this.nodes.length + 1),
                    type: 'rack',
                    zone: 'tool_crib',
                    location_id: '',
                    sizePreset: 'medium',
                    pos_x: 20,
                    pos_y: 20,
                    width: 18,
                    height: 16,
                    icon: 'rack',
                    color_theme: 'blue',
                    description: '',
                }
            };
        },

        openEditNodeModal(node) {
            this.drawerOpen = false;
            this.nodeModal = {
                open: true,
                isEdit: true,
                showCoords: false,
                data: {
                    id: node.id,
                    name: node.name,
                    code: node.code,
                    type: node.type,
                    zone: node.zone,
                    location_id: node.location_id || '',
                    sizePreset: 'custom',
                    pos_x: node.pos_x,
                    pos_y: node.pos_y,
                    width: node.width,
                    height: node.height,
                    icon: node.icon || node.type,
                    color_theme: node.color_theme || 'blue',
                    description: node.description || '',
                }
            };
        },

        onSizePresetChange() {
            const p = this.nodeModal.data.sizePreset;
            if (p === 'small') {
                this.nodeModal.data.width = 14;
                this.nodeModal.data.height = 12;
            } else if (p === 'medium') {
                this.nodeModal.data.width = 18;
                this.nodeModal.data.height = 16;
            } else if (p === 'large') {
                this.nodeModal.data.width = 24;
                this.nodeModal.data.height = 20;
            }
        },

        zoneModal: {
            open: false,
            isEdit: false,
            data: {
                id: null,
                name: '',
                code: '',
                color: 'blue',
                pos_x: 10,
                pos_y: 10,
                width: 35,
                height: 30,
                description: ''
            }
        },

        deleteZoneModal: {
            open: false,
            zoneId: null,
            zoneName: ''
        },

        openAddZoneModal() {
            const nextIdx = (this.zones ? this.zones.length : 0) + 1;
            this.zoneModal = {
                open: true,
                isEdit: false,
                data: {
                    id: null,
                    name: 'Zona Baru ' + nextIdx,
                    code: 'zone_' + nextIdx,
                    color: 'indigo',
                    pos_x: 10,
                    pos_y: 10,
                    width: 35,
                    height: 30,
                    description: ''
                }
            };
        },

        openEditZoneModal(zone) {
            this.zoneModal = {
                open: true,
                isEdit: true,
                data: {
                    id: zone.id,
                    name: zone.name,
                    code: zone.code,
                    color: zone.color || 'blue',
                    pos_x: zone.pos_x,
                    pos_y: zone.pos_y,
                    width: zone.width,
                    height: zone.height,
                    description: zone.description || ''
                }
            };
        },

        openDeleteZoneModal(zone) {
            this.deleteZoneModal = {
                open: true,
                zoneId: zone.id,
                zoneName: zone.name
            };
        },

        async confirmDeleteZone() {
            if (!this.deleteZoneModal.zoneId) return;
            const zoneId = this.deleteZoneModal.zoneId;
            try {
                const res = await fetch('{{ url('/admin/workshop-layout/zones') }}/' + zoneId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        _method: 'DELETE'
                    })
                });
                const data = await res.json();
                if (data.success) {
                    this.zones = this.zones.filter(z => z.id != zoneId);
                    this.deleteZoneModal.open = false;
                    return;
                } else if (data.message) {
                    alert(data.message);
                    this.deleteZoneModal.open = false;
                    return;
                }
            } catch(e) {}

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url('/admin/workshop-layout/zones') }}/' + zoneId;
            form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
            document.body.appendChild(form);
            form.submit();
        },

        getZoneBoxClass(zone) {
            const color = zone.color || 'blue';
            return `zone-color-${color}`;
        },

        getZoneTextClass(zone) {
            const color = zone.color || 'blue';
            const map = {
                'blue': 'text-blue-600 dark:text-blue-400',
                'emerald': 'text-emerald-600 dark:text-emerald-400',
                'cyan': 'text-cyan-600 dark:text-cyan-400',
                'rose': 'text-rose-600 dark:text-rose-400',
                'slate': 'text-slate-600 dark:text-slate-400',
                'amber': 'text-amber-600 dark:text-amber-400',
                'purple': 'text-purple-600 dark:text-purple-400',
                'indigo': 'text-indigo-600 dark:text-indigo-400',
            };
            return map[color] || 'text-blue-600 dark:text-blue-400';
        },

        onZoneChange() {
            const zCode = this.nodeModal.data.zone;
            const zObj = (this.zones || []).find(z => z.code === zCode);
            if (zObj) {
                this.nodeModal.data.pos_x = Math.min(90, Math.max(2, parseFloat(zObj.pos_x) + 2));
                this.nodeModal.data.pos_y = Math.min(90, Math.max(2, parseFloat(zObj.pos_y) + 4));
                this.nodeModal.data.color_theme = zObj.color || 'blue';
            }
        },

        openDeleteModal(node) {
            this.nodeModal.open = false;
            this.drawerOpen = false;
            this.deleteModal = { open: true, nodeId: node.id || node, nodeName: node.name || 'stasiun ini' };
        },

        async confirmDelete() {
            if (!this.deleteModal.nodeId) return;
            const nodeId = this.deleteModal.nodeId;
            try {
                const res = await fetch('{{ url('/admin/workshop-layout/nodes') }}/' + nodeId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        _method: 'DELETE'
                    })
                });
                const data = await res.json();
                if (data.success) {
                    this.nodes = this.nodes.filter(n => n.id != nodeId);
                    this.summary.total_nodes = this.nodes.length;
                    this.deleteModal.open = false;
                    this.selectedNode = null;
                    this.drawerOpen = false;
                    return;
                }
            } catch(e) {}

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url('/admin/workshop-layout/nodes') }}/' + nodeId;
            form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
            document.body.appendChild(form);
            form.submit();
        },

        deleteNode(id) {
            this.deleteModal = { open: true, nodeId: id, nodeName: this.nodeModal.data.name || 'stasiun ini' };
            this.nodeModal.open = false;
        },

        getZoneLabel(zone) {
            const zObj = (this.zones || []).find(z => z.code === zone);
            if (zObj) return zObj.name;
            const labels = {
                'machining': 'Zona A: Machining & Fabrikasi',
                'tool_crib': 'Zona B: Tool Crib & Storage',
                'assembly': 'Zona C: Perakitan & Solder',
                'safety': 'Zona D: Keselamatan K3',
                'logistics': 'Zona E: Logistik Dock',
            };
            return labels[zone] || zone || 'Area Bengkel';
        },

        getNodeBackgroundClass(node) {
            if (this.layerMode === 'safety' && (node.zone === 'safety' || node.telemetry?.high_risk_count > 0)) {
                return 'bg-rose-500/15 border-rose-500/50 text-rose-900 dark:text-rose-100 ring-2 ring-rose-400/50';
            }
            if (this.layerMode === 'logistics' && node.telemetry?.low_stock_count > 0) {
                return 'bg-amber-500/15 border-amber-500/50 text-amber-900 dark:text-amber-100 ring-2 ring-amber-400/50';
            }
            if (this.layerMode === 'maintenance' && (node.telemetry?.in_maintenance_count > 0 || node.telemetry?.damaged_items_count > 0)) {
                return 'bg-violet-500/15 border-violet-500/50 text-violet-900 dark:text-violet-100 ring-2 ring-violet-400/50';
            }
            return (node.telemetry?.status_color?.bg || 'bg-slate-50') + ' ' + (node.telemetry?.status_color?.border || 'border-slate-200');
        },

        getNodeCodeBadgeClass(node) {
            const theme = node.color_theme || 'blue';
            const map = {
                'blue': 'bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300',
                'emerald': 'bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300',
                'cyan': 'bg-cyan-100 dark:bg-cyan-900/60 text-cyan-700 dark:text-cyan-300',
                'amber': 'bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300',
                'rose': 'bg-rose-100 dark:bg-rose-900/60 text-rose-700 dark:text-rose-300',
                'purple': 'bg-purple-100 dark:bg-purple-900/60 text-purple-700 dark:text-purple-300',
                'slate': 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300',
            };
            return map[theme] || map['blue'];
        },

        getNodeBeaconClass(node) {
            return node.telemetry?.status_color?.badge || 'bg-emerald-500';
        },

        getNodeIconBgClass(node) {
            const theme = node.color_theme || 'blue';
            const map = {
                'blue': 'bg-blue-600 text-white shadow-blue-500/30',
                'emerald': 'bg-emerald-600 text-white shadow-emerald-500/30',
                'cyan': 'bg-cyan-600 text-white shadow-cyan-500/30',
                'amber': 'bg-amber-600 text-white shadow-amber-500/30',
                'rose': 'bg-rose-600 text-white shadow-rose-500/30',
                'purple': 'bg-purple-600 text-white shadow-purple-500/30',
                'slate': 'bg-slate-700 text-white shadow-slate-500/30',
            };
            return map[theme] || map['blue'];
        },

        renderNodeIcon(iconType) {
            switch(iconType) {
                case 'lathe':
                case 'machine':
                    return `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`;
                case 'mill':
                    return `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>`;
                case 'drill':
                    return `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>`;
                case 'rack':
                    return `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16M6 4v16M18 4v16"/></svg>`;
                case 'cabinet':
                case 'micrometer':
                    return `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>`;
                case 'workbench':
                case 'table':
                    return `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 11h16M6 7v10M18 7v10"/></svg>`;
                case 'solder':
                    return `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>`;
                case 'box':
                    return `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>`;
                case 'shield':
                case 'safety_kiosk':
                    return `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>`;
                case 'flame':
                    return `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/></svg>`;
                case 'truck':
                case 'logistics_bay':
                    return `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>`;
                default:
                    return `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>`;
            }
        }
    };
}
</script>

<div class="max-w-[1700px] mx-auto px-4 sm:px-6 lg:px-8 py-5 space-y-5"
     x-data="workshopDigitalTwin({{ Js::from($nodesJson) }}, {{ Js::from($summary) }}, {{ Js::from($zonesJson) }})"
     x-init="initComponent()">

    <div class="ws-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-blue-600 via-cyan-500 to-emerald-500"></div>
        <div class="p-5 sm:p-6">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-600 to-cyan-500 flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-500/20 text-white">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="ws-title text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Denah Interaktif Bengkel & Digital Twin 2D</h1>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-beacon"></span>
                                2D Live Telemetry
                            </span>
                        </div>
                        <p class="ws-sub text-xs sm:text-sm text-gray-500 mt-1">Pemetaan spasial live status rak alat, mesin perkakas, meja kerja, dan pos keselamatan (K3) bengkel vokasi</p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 flex-wrap">

                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-50 dark:bg-slate-800/80 border border-gray-200 dark:border-slate-700 text-xs text-gray-600 dark:text-slate-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Sync: <strong x-text="summary.last_synced_at || 'Baru Saja'"></strong></span>
                        <button type="button" @click="fetchLiveData()" class="text-blue-600 hover:text-blue-700 ml-1 font-semibold" title="Refresh Live Data">
                            <svg class="w-3.5 h-3.5 inline" :class="isLoading ? 'animate-spin' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>
                    </div>

                    <button type="button"
                            @click="openAddNodeModal()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-sm shadow-blue-500/20 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Stasiun
                    </button>

                    <button type="button"
                            @click="openAddZoneModal()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shadow-indigo-500/20 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Tambah Zona
                    </button>

                    <button type="button"
                            @click="toggleEditorMode()"
                            :class="isEditorMode ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 border border-gray-200 dark:border-slate-700 hover:bg-gray-50'"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span x-text="isEditorMode ? 'Selesai Desain' : 'Mode Desain'"></span>
                    </button>

                    <form action="{{ route('admin.workshop.reset-default') }}" method="POST" onsubmit="return confirm('Reset denah ke blueprint standar vokasi?')">
                        @csrf
                        <button type="submit"
                                x-show="isEditorMode"
                                x-cloak
                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-300 border border-gray-200 dark:border-slate-700 transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Blueprint
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mt-5 pt-4 border-t border-gray-100 dark:border-slate-800">
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700/60 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-slate-200/80 dark:bg-slate-700 flex items-center justify-center text-slate-700 dark:text-slate-300 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 dark:text-slate-400">Total Stasiun/Rak</div>
                        <div class="text-lg font-bold text-gray-900 dark:text-white" x-text="summary.total_nodes"></div>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-emerald-50/70 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-800/40 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-emerald-500/20 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Siap Operasi</div>
                        <div class="text-lg font-bold text-emerald-800 dark:text-emerald-300" x-text="summary.operational_nodes"></div>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-blue-50/70 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-800/40 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-500/20 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-blue-700 dark:text-blue-400">Sedang Dipinjam</div>
                        <div class="text-lg font-bold text-blue-800 dark:text-blue-300" x-text="summary.total_active_loans + ' Unit'"></div>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-amber-50/70 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-800/40 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-amber-500/20 text-amber-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-amber-700 dark:text-amber-400">Stok Menipis (ROP)</div>
                        <div class="text-lg font-bold text-amber-800 dark:text-amber-300" x-text="summary.total_low_stock_rop"></div>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-rose-50/70 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-800/40 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-rose-500/20 text-rose-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-rose-700 dark:text-rose-400">Peringatan Kritis</div>
                        <div class="text-lg font-bold text-rose-800 dark:text-rose-300" x-text="summary.critical_nodes"></div>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-violet-50/70 dark:bg-violet-950/20 border border-violet-100 dark:border-violet-800/40 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-violet-500/20 text-violet-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-violet-700 dark:text-violet-400">Servis & Kerusakan</div>
                        <div class="text-lg font-bold text-violet-800 dark:text-violet-300" x-text="summary.total_damaged_items + summary.maintenance_nodes"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ws-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col md:flex-row items-center justify-between gap-4">

        <div class="flex items-center gap-1.5 flex-wrap w-full md:w-auto">
            <span class="text-xs font-bold text-gray-500 dark:text-slate-400 mr-1 uppercase tracking-wider">Layer Mode:</span>

            <button type="button"
                    @click="setLayerMode('all')"
                    :class="layerMode === 'all' ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900 shadow-sm' : 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-200'"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                <span>🗺️</span>
                <span>Semua</span>
            </button>

            <button type="button"
                    @click="setLayerMode('availability')"
                    :class="layerMode === 'availability' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-200'"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Ketersediaan Alat</span>
            </button>

            <button type="button"
                    @click="setLayerMode('maintenance')"
                    :class="layerMode === 'maintenance' ? 'bg-violet-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-200'"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                <span>⚙️</span>
                <span>Pemeliharaan & Servis</span>
            </button>

            <button type="button"
                    @click="setLayerMode('safety')"
                    :class="layerMode === 'safety' ? 'bg-rose-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-200'"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                <span>🛡️</span>
                <span>Peta Bahaya K3 & APD</span>
            </button>

            <button type="button"
                    @click="setLayerMode('logistics')"
                    :class="layerMode === 'logistics' ? 'bg-amber-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-200'"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                <span>📦</span>
                <span>Smart ROP Alert</span>
            </button>

            <button type="button"
                    @click="setLayerMode('calibration')"
                    :class="layerMode === 'calibration' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-200'"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                <span>⏱️</span>
                <span>Kalibrasi Presisi</span>
            </button>
        </div>

        <div class="relative w-full md:w-80">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text"
                   x-model="searchQuery"
                   placeholder="Cari alat / rak (Bubut, Solder, Baut...)"
                   class="w-full pl-9 pr-8 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <button type="button"
                    x-show="searchQuery"
                    @click="clearSearch()"
                    class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-gray-600">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <div x-show="isEditorMode"
         x-cloak
         class="p-3.5 bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-800 rounded-xl flex items-center justify-between text-xs text-indigo-900 dark:text-indigo-200">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
            <span><strong>Mode Desain Aktif:</strong> Klik & geser (drag) stasiun atau zona pada denah untuk memindahkan posisinya, atau gunakan ikon sudut kanan bawah zona untuk mengubah ukuran.</span>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="savePositions()" :disabled="!hasUnsavedPositions || isSaving"
                    :class="hasUnsavedPositions ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                    class="px-3.5 py-1.5 rounded-lg font-bold text-xs transition shadow-sm">
                <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Perubahan Posisi'"></span>
            </button>
            <button type="button" @click="toggleEditorMode()" class="px-3 py-1.5 rounded-lg border border-indigo-300 text-indigo-700 hover:bg-indigo-100 dark:border-indigo-700 dark:text-indigo-300 font-semibold">
                Keluar
            </button>
        </div>
    </div>

    <div class="ws-card bg-white rounded-2xl shadow-sm border border-gray-200 dark:border-slate-800 overflow-hidden relative"
         :class="isFullscreen ? 'fixed inset-0 z-50 rounded-none' : ''">

        <div style="position: absolute; top: 16px; right: 16px; z-index: 40;"
             class="flex items-center gap-1.5 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md p-1.5 rounded-xl shadow-md border border-gray-200 dark:border-slate-700">
            <button type="button" @click="zoomIn()" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300" title="Zoom In (+)">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </button>
            <span class="text-[11px] font-bold text-gray-500 dark:text-slate-400 px-1" x-text="Math.round(zoomLevel * 100) + '%'"></span>
            <button type="button" @click="zoomOut()" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300" title="Zoom Out (-)">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            </button>
            <button type="button" @click="resetZoom()" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300" title="Reset View">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
            </button>
            <div class="h-4 w-px bg-gray-200 dark:bg-slate-700 mx-0.5"></div>
            <button type="button" @click="toggleFullscreen()" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300" :title="isFullscreen ? 'Keluar Fullscreen' : 'Fullscreen'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/></svg>
            </button>
        </div>

        <div style="position: absolute; bottom: 16px; left: 16px; z-index: 40;"
             class="hidden sm:flex items-center gap-3 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md px-3.5 py-2 rounded-xl shadow-md border border-gray-200 dark:border-slate-700 text-[11px] font-medium text-gray-600 dark:text-slate-300">
            <span class="font-bold text-gray-900 dark:text-white uppercase tracking-wider text-[10px]">Status:</span>
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Optimal</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                <span>Dipinjam</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                <span>Stok ROP</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                <span>Kritis</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-violet-500"></span>
                <span>Servis</span>
            </div>
        </div>

        <div class="w-full ws-canvas-light dark:ws-canvas-bg cursor-grab active:cursor-grabbing"
             style="height: 720px; min-height: 720px; position: relative; overflow: hidden; touch-action: none;"
             :style="`background-position: ${panOffset.x}px ${panOffset.y}px;`"
             @mousedown="startPan($event)"
             @mousemove="onPan($event)"
             @mouseup="endPan()"
             @mouseleave="endPan()"
             @touchstart="startPan($event)"
             @touchmove="onPan($event)"
             @touchend="endPan()"
             @touchcancel="endPan()"
             @wheel.prevent="onWheel($event)">

            <div class="absolute inset-0 origin-center transition-transform duration-75 ease-out"
                 :style="`transform: translate(${panOffset.x}px, ${panOffset.y}px) scale(${zoomLevel}); width: 100%; height: 100%;`">

                <template x-for="z in zones" :key="z.id">
                    <div class="ws-zone absolute rounded-2xl p-3 select-none"
                         :class="[
                             getZoneBoxClass(z),
                             isEditorMode ? 'cursor-move pointer-events-auto border-2 border-dashed ring-2 ring-transparent hover:ring-indigo-400 opacity-95' : 'pointer-events-none',
                             isDraggingZoneId === z.id ? 'is-dragging ring-4 ring-indigo-500 shadow-2xl z-20' : '',
                             isResizingZoneId === z.id ? 'is-resizing ring-4 ring-amber-500 shadow-2xl z-20' : ''
                         ]"
                         :style="`left: ${z.pos_x}%; top: ${z.pos_y}%; width: ${z.width}%; height: ${z.height}%;`"
                         @mousedown="onZoneMouseDown(z, $event)"
                         @touchstart.stop="onZoneMouseDown(z, $event)">
                        <div class="flex items-center justify-between gap-1.5 pointer-events-auto">
                            <div class="flex items-center gap-1.5 font-extrabold text-[11px] tracking-wider uppercase"
                                 :class="getZoneTextClass(z)">
                                <span x-show="isEditorMode" class="text-xs text-indigo-500 dark:text-indigo-400 cursor-move" title="Geser Zona">⠿</span>
                                <span x-text="z.name"></span>
                            </div>
                            <div x-show="isEditorMode" class="flex items-center gap-1 pointer-events-auto bg-white/90 dark:bg-slate-900/90 rounded-lg p-0.5 shadow-sm border border-gray-200 dark:border-slate-700">
                                <button type="button" @click.stop="openEditZoneModal(z)" class="p-1 rounded hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 hover:text-blue-600" title="Edit Zona">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <button type="button" @click.stop="openDeleteZoneModal(z)" class="p-1 rounded hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 hover:text-rose-600" title="Hapus Zona">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>

                        <div x-show="isEditorMode"
                             @mousedown.stop="onZoneResizeMouseDown(z, $event)"
                             @touchstart.stop="onZoneResizeMouseDown(z, $event)"
                             class="absolute bottom-1.5 right-1.5 w-5 h-5 flex items-center justify-center cursor-nwse-resize pointer-events-auto bg-white/90 dark:bg-slate-900/90 rounded-md border border-gray-300 dark:border-slate-600 hover:bg-indigo-50 dark:hover:bg-indigo-950 text-gray-500 hover:text-indigo-600 transition shadow-xs"
                             title="Tarik untuk mengubah ukuran zona">
                            <svg class="w-3 h-3" viewBox="0 0 16 16" fill="currentColor">
                                <circle cx="12" cy="12" r="1.5"/>
                                <circle cx="12" cy="7" r="1.5"/>
                                <circle cx="7" cy="12" r="1.5"/>
                            </svg>
                        </div>
                    </div>
                </template>

                <div x-show="nodes.length === 0"
                     class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center pointer-events-none">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-slate-800 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-3 shadow-inner pointer-events-auto">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white pointer-events-auto">Denah Bengkel Masih Kosong</h3>
                    <p class="text-xs text-gray-500 dark:text-slate-400 max-w-sm mt-1 pointer-events-auto">Belum ada stasiun atau rak perkakas yang terpasang di denah ini. Anda dapat menambahkan stasiun baru atau memuat blueprint standar.</p>
                    <div class="flex items-center gap-2.5 mt-4 pointer-events-auto">
                        <button type="button" @click="openAddNodeModal()" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-sm">
                            + Tambah Stasiun
                        </button>
                        <button type="button" @click="openAddZoneModal()" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm">
                            + Tambah Zona
                        </button>
                        <form action="{{ route('admin.workshop.reset-default') }}" method="POST" onsubmit="return confirm('Muat blueprint standar vokasi?')">
                            @csrf
                            <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-300 border border-gray-200 dark:border-slate-700">
                                Muat Blueprint Standar
                            </button>
                        </form>
                    </div>
                </div>

                <template x-for="node in filteredNodes" :key="node.id">
                    <div class="ws-node absolute rounded-2xl cursor-pointer p-3 flex flex-col justify-between shadow-md border backdrop-blur-sm bg-white/95 dark:bg-slate-900/95"
                         :class="[
                            getNodeBackgroundClass(node),
                            selectedNode && selectedNode.id === node.id ? 'ring-4 ring-blue-500 shadow-xl' : '',
                            isSpotlightNode(node) ? 'ring-4 ring-amber-400 animate-bounce' : '',
                            isDraggingNodeId === node.id ? 'is-dragging' : ''
                         ]"
                         :style="`left: ${node.pos_x}%; top: ${node.pos_y}%; width: ${node.width}%; height: ${node.height}%;`"
                         @click="onNodeClick(node, $event)"
                         @mousedown="onNodeMouseDown(node, $event)"
                         @touchstart.stop="onNodeMouseDown(node, $event)">

                        <div x-show="isSpotlightNode(node)" class="absolute -inset-3 rounded-2xl bg-amber-400/40 animate-radar pointer-events-none"></div>

                        <div class="flex items-center justify-between gap-1 pointer-events-none">
                            <span class="text-[10px] font-black tracking-wider px-2 py-0.5 rounded-md uppercase"
                                  :class="getNodeCodeBadgeClass(node)"
                                  x-text="node.code"></span>

                            <div class="flex items-center gap-1.5">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                                          :class="getNodeBeaconClass(node)"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5"
                                          :class="getNodeBeaconClass(node)"></span>
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5 my-1 overflow-hidden pointer-events-none">
                            <div class="w-7 h-7 rounded-xl flex items-center justify-center flex-shrink-0"
                                 :class="getNodeIconBgClass(node)"
                                 x-html="renderNodeIcon(node.icon || node.type)">
                            </div>
                            <div class="overflow-hidden">
                                <div class="text-xs font-bold leading-tight truncate text-gray-900 dark:text-white" x-text="node.name"></div>
                                <div class="text-[10px] text-gray-500 dark:text-slate-400 truncate" x-text="node.location_name || getZoneLabel(node.zone)"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-1 pt-1 border-t border-gray-100/60 dark:border-slate-800/60 text-[10px] pointer-events-none">
                            <span class="font-bold text-gray-700 dark:text-slate-300">
                                <span x-text="(node.telemetry?.total_items || 0) + ' Alat'"></span>
                            </span>

                            <div class="flex items-center gap-1">
                                <template x-if="node.telemetry?.active_loans_count > 0">
                                    <span class="px-1.5 py-0.5 rounded bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 font-extrabold" title="Sedang Dipinjam">
                                        <span x-text="node.telemetry.active_loans_count + ' Pinjam'"></span>
                                    </span>
                                </template>
                                <template x-if="node.telemetry?.low_stock_count > 0">
                                    <span class="px-1.5 py-0.5 rounded bg-amber-100 dark:bg-amber-900/60 text-amber-800 dark:text-amber-300 font-extrabold" title="Stok Menipis (ROP)">
                                        ROP!
                                    </span>
                                </template>
                            </div>
                        </div>

                        <template x-if="isEditorMode">
                            <div class="absolute -top-2 -right-2 w-5 h-5 bg-indigo-600 text-white rounded-full flex items-center justify-center text-[10px] shadow-sm pointer-events-none font-bold">
                                ✥
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="drawerOpen"
             x-cloak
             class="fixed inset-0 z-[9998] ws-drawer-overlay overflow-hidden"
             style="z-index: 99999 !important;"
             role="dialog" aria-modal="true">

        <div x-show="drawerOpen"
             x-transition:enter="ease-in-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in-out duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="closeDrawer()"
             class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10" style="z-index: 99999 !important;">
            <div x-show="drawerOpen"
                 x-transition:enter="transform transition ease-in-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-screen max-w-md sm:max-w-lg bg-white dark:bg-slate-900 shadow-2xl border-l border-gray-100 dark:border-slate-800 flex flex-col justify-between h-full">

                <div class="p-5 border-b border-gray-100 dark:border-slate-800 flex items-start justify-between bg-gray-50/50 dark:bg-slate-900/50 flex-shrink-0">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md"
                             :class="selectedNode ? getNodeIconBgClass(selectedNode) : 'bg-blue-600 text-white'"
                             x-html="selectedNode ? renderNodeIcon(selectedNode.icon || selectedNode.type) : ''">
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-gray-900 text-white dark:bg-slate-100 dark:text-gray-900"
                                      x-text="selectedNode?.code"></span>
                                <span class="text-xs font-semibold text-gray-500 dark:text-slate-400"
                                      x-text="getZoneLabel(selectedNode?.zone)"></span>
                            </div>
                            <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mt-1 leading-snug" x-text="selectedNode?.name"></h2>
                        </div>
                    </div>
                    <button type="button" @click="closeDrawer()" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-slate-200 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 min-h-0 overflow-y-auto p-5 sm:p-6 space-y-5">

                    <template x-if="selectedNode">
                        <div class="p-3.5 rounded-xl border flex items-center justify-between"
                             :class="selectedNode.telemetry?.status_color?.bg + ' ' + selectedNode.telemetry?.status_color?.border">
                            <div class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full animate-beacon"
                                      :class="selectedNode.telemetry?.status_color?.badge"></span>
                                <div>
                                    <div class="text-xs font-extrabold uppercase tracking-wider"
                                         :class="selectedNode.telemetry?.status_color?.text"
                                         x-text="selectedNode.telemetry?.status_label"></div>
                                    <div class="text-[11px] text-gray-500 dark:text-slate-400"
                                         x-text="selectedNode.location_name ? 'Database Lokasi: ' + selectedNode.location_name : 'Stasiun fisik mandiri'"></div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="selectedNode?.description">
                        <div class="text-xs text-gray-600 dark:text-slate-300 leading-relaxed bg-gray-50 dark:bg-slate-800/50 p-3 rounded-xl border border-gray-100 dark:border-slate-800"
                             x-text="selectedNode.description"></div>
                    </template>

                    <div class="grid grid-cols-3 gap-2.5 text-center">
                        <div class="p-3 rounded-xl bg-gray-50 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-800">
                            <div class="text-lg font-black text-gray-900 dark:text-white" x-text="selectedNode?.telemetry?.total_stock || 0"></div>
                            <div class="text-[10px] font-semibold text-gray-500 uppercase mt-0.5">Total Stok</div>
                        </div>
                        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-800/40">
                            <div class="text-lg font-black text-emerald-600 dark:text-emerald-400" x-text="selectedNode?.telemetry?.available_borrow_stock || 0"></div>
                            <div class="text-[10px] font-semibold text-emerald-700 uppercase mt-0.5">Tersedia Pinjam</div>
                        </div>
                        <div class="p-3 rounded-xl bg-blue-50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-800/40">
                            <div class="text-lg font-black text-blue-600 dark:text-blue-400" x-text="selectedNode?.telemetry?.active_loans_count || 0"></div>
                            <div class="text-[10px] font-semibold text-blue-700 uppercase mt-0.5">Sedang Dipakai</div>
                        </div>
                    </div>

                    <template x-if="selectedNode?.telemetry?.active_borrowers?.length > 0">
                        <div class="space-y-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Peminjam Aktif Saat Ini</span>
                            </h3>
                            <div class="space-y-2">
                                <template x-for="loan in selectedNode.telemetry.active_borrowers" :key="loan.user_name + loan.item_name">
                                    <div class="p-3 rounded-xl bg-blue-50/50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-800/40 flex items-center justify-between text-xs">
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-white" x-text="loan.user_name"></div>
                                            <div class="text-gray-500 text-[11px]" x-text="loan.item_name + ' (' + loan.jumlah + ' unit)'"></div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-[10px] text-gray-500">Jatuh Tempo:</div>
                                            <div class="font-semibold text-blue-700 dark:text-blue-300" x-text="loan.due_date"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <div class="space-y-2.5">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>Daftar Perkakas di Stasiun Ini (<span x-text="selectedNode?.telemetry?.items?.length || 0"></span>)</span>
                        </h3>

                        <div class="space-y-2">
                            <template x-for="item in selectedNode?.telemetry?.items || []" :key="item.id">
                                <div class="p-3 rounded-xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-800/80 shadow-sm flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2.5 overflow-hidden">
                                        <template x-if="item.gambar">
                                            <img :src="item.gambar" alt="Tool" class="w-9 h-9 rounded-lg object-cover flex-shrink-0 border border-gray-100">
                                        </template>
                                        <template x-if="!item.gambar">
                                            <div class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-400 flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
                                            </div>
                                        </template>
                                        <div class="overflow-hidden">
                                            <div class="text-xs font-bold text-gray-900 dark:text-white truncate" x-text="item.nama"></div>
                                            <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                                <span class="text-[10px] text-gray-500" x-text="item.kode"></span>
                                                <template x-if="item.is_below_rop">
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-amber-100 text-amber-800">Low ROP</span>
                                                </template>
                                                <template x-if="item.safety_risk_level === 'high'">
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-rose-100 text-rose-800">High Risk</span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <div class="text-xs font-black text-gray-900 dark:text-white" x-text="item.stok_peminjaman + ' Siap'"></div>
                                        <div class="text-[10px] text-gray-400" x-text="'Total: ' + item.stok_total"></div>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!selectedNode?.telemetry?.items || selectedNode.telemetry.items.length === 0">
                                <div class="p-5 text-center text-xs text-gray-400 bg-gray-50 dark:bg-slate-800/40 rounded-xl border border-dashed border-gray-200 dark:border-slate-700">
                                    Belum ada perkakas yang terhubung ke lokasi stasiun ini.
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50 dark:bg-slate-900 flex-shrink-0">

                    <div class="flex items-center gap-2 mb-3">
                        <button type="button"
                                @click="openEditNodeModal(selectedNode)"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Stasiun
                        </button>
                        <button type="button"
                                @click="openDeleteModal(selectedNode)"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 dark:bg-rose-900/20 dark:hover:bg-rose-900/40 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 text-xs font-bold transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <a :href="'{{ route('admin.defect-scanner.scan') }}'"
                           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold shadow-sm transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                            Scan Cacat AI
                        </a>
                        <button type="button" @click="closeDrawer()" class="ml-auto px-4 py-2 rounded-xl bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 text-xs font-bold text-gray-700 dark:text-slate-200 transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="nodeModal.open"
             x-cloak
             class="fixed inset-0 z-[9999] ws-modal-overlay overflow-y-auto"
             style="z-index: 99999 !important;"
             role="dialog"
             aria-modal="true"
             @keydown.escape.window="nodeModal.open = false">

            <div x-show="nodeModal.open"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="nodeModal.open = false"
                 class="fixed inset-0 bg-gray-950/60 backdrop-blur-sm transition-opacity"></div>

            <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 text-center">

                <div x-show="nodeModal.open"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2 sm:translate-y-0"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-2 sm:translate-y-0"
                     @click.stop
                     class="relative transform text-left bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-800 max-w-lg w-full flex flex-col max-h-[85vh] my-auto overflow-hidden">

                <div class="px-5 py-3.5 sm:px-6 sm:py-4 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between flex-shrink-0 bg-gray-50/70 dark:bg-slate-850">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-white shadow-sm flex-shrink-0"
                             :class="nodeModal.isEdit ? 'bg-indigo-600' : 'bg-blue-600'">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white leading-tight"
                                x-text="nodeModal.isEdit ? 'Edit Data Stasiun / Rak' : 'Tambah Stasiun / Rak Baru'"></h3>
                            <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-0.5">Konfigurasi visual spasial & relasi database denah</p>
                        </div>
                    </div>
                    <button type="button"
                            @click="nodeModal.open = false"
                            class="p-2 rounded-xl text-gray-400 hover:text-gray-600 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 transition"
                            title="Tutup Modal (Esc)">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form :action="nodeModal.isEdit ? '{{ url('/admin/workshop-layout/nodes') }}/' + nodeModal.data.id : '{{ route('admin.workshop.store') }}'"
                      method="POST"
                      class="flex flex-col flex-1 min-h-0 overflow-hidden">
                    @csrf
                    <input type="hidden" name="_method" :value="nodeModal.isEdit ? 'PUT' : 'POST'">

                    <div class="p-5 sm:p-6 overflow-y-auto min-h-0 space-y-4 flex-1">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">
                                    Nama Stasiun / Rak <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="name" x-model="nodeModal.data.name" required placeholder="Contoh: Mesin Bubut #1"
                                       class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">
                                    Kode Spasial <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="code" x-model="nodeModal.data.code" required placeholder="Contoh: MCH-01"
                                       class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 uppercase focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-mono">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">
                                    Tipe Stasiun <span class="text-rose-500">*</span>
                                </label>
                                <select name="type" x-model="nodeModal.data.type" required
                                        class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="machine">Mesin Berat (Machine)</option>
                                    <option value="rack">Rak Perkakas (Storage Rack)</option>
                                    <option value="cabinet">Lemari Tertutup (Cabinet)</option>
                                    <option value="workbench">Meja Kerja (Workbench)</option>
                                    <option value="safety_kiosk">Kios K3 / APD (Safety Kiosk)</option>
                                    <option value="logistics_bay">Dermaga Logistik (Logistics)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">
                                    Zona Bengkel <span class="text-rose-500">*</span>
                                </label>
                                <select name="zone" x-model="nodeModal.data.zone" @change="onZoneChange()" required
                                        class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <template x-for="z in zones" :key="z.code">
                                        <option :value="z.code" x-text="z.name" :selected="nodeModal.data.zone === z.code"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300">
                                    Hubungkan ke Lokasi Database
                                </label>
                                <span class="text-[10px] text-gray-400">Sinkronisasi stok otomatis</span>
                            </div>
                            <select name="location_id" x-model="nodeModal.data.location_id"
                                    class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">-- Tanpa Relasi Lokasi (Stasiun Fisik Mandiri) --</option>
                                @foreach($availableLocations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->kode ?? 'No Code' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">Ikon Stasiun</label>
                                <select name="icon" x-model="nodeModal.data.icon" required
                                        class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="lathe">Mesin Bubut (Lathe)</option>
                                    <option value="mill">Mesin Frais (Milling)</option>
                                    <option value="drill">Bor Duduk / Gerinda</option>
                                    <option value="rack">Rak Bertingkat (Rack)</option>
                                    <option value="micrometer">Alat Ukur (Cabinet)</option>
                                    <option value="table">Meja Kerja (Workbench)</option>
                                    <option value="solder">Stasiun Solder</option>
                                    <option value="box">Kotak Fastener / Baut</option>
                                    <option value="shield">Kios Inspeksi APD</option>
                                    <option value="flame">APAR & First Aid</option>
                                    <option value="truck">Logistik Dock</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">Ukuran Blok Denah</label>
                                <select x-model="nodeModal.data.sizePreset" @change="onSizePresetChange()"
                                        class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="small">Kecil (Kios / Bor / Kotak)</option>
                                    <option value="medium">Sedang (Rak / Bubut / Meja)</option>
                                    <option value="large">Besar (Dermaga / Zona Luas)</option>
                                    <option value="custom">Kustom (Atur Manual)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">Aksen Warna Denah</label>
                            <select name="color_theme" x-model="nodeModal.data.color_theme" required
                                    class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="blue">🔵 Biru — Machining & Fabrikasi</option>
                                <option value="emerald">🟢 Hijau — Tool Crib & Rak Utama</option>
                                <option value="cyan">💠 Cyan — Perakitan & Elektronika</option>
                                <option value="amber">🟡 Amber — Fastener & Perhatian</option>
                                <option value="rose">🔴 Merah — Safety K3 & APAR</option>
                                <option value="purple">🟣 Ungu — Alat Ukur Presisi</option>
                                <option value="slate">⚪ Abu-abu — Dermaga Logistik</option>
                            </select>
                        </div>

                        <div class="rounded-xl border border-gray-200/80 dark:border-slate-800 bg-gray-50/40 dark:bg-slate-800/40 p-3">
                            <button type="button"
                                    @click="nodeModal.showCoords = !nodeModal.showCoords"
                                    class="w-full flex items-center justify-between text-xs text-indigo-600 dark:text-indigo-400 font-semibold hover:text-indigo-700">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
                                    <span>Atur Koordinat & Dimensi Presisi (%)</span>
                                </span>
                                <span class="text-[11px]" x-text="nodeModal.showCoords ? '▲ Tutup' : '▼ Buka'"></span>
                            </button>

                            <div x-show="nodeModal.showCoords"
                                 x-transition
                                 class="mt-3 pt-3 border-t border-gray-200 dark:border-slate-700 grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 mb-1">Pos X (%)</label>
                                    <input type="number" step="0.5" min="0" max="200" name="pos_x" x-model="nodeModal.data.pos_x" required
                                           class="w-full text-xs rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 py-1.5 px-2 text-gray-900 dark:text-white font-mono">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 mb-1">Pos Y (%)</label>
                                    <input type="number" step="0.5" min="0" max="200" name="pos_y" x-model="nodeModal.data.pos_y" required
                                           class="w-full text-xs rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 py-1.5 px-2 text-gray-900 dark:text-white font-mono">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 mb-1">Lebar (%)</label>
                                    <input type="number" step="0.5" min="2" max="150" name="width" x-model="nodeModal.data.width" required
                                           class="w-full text-xs rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 py-1.5 px-2 text-gray-900 dark:text-white font-mono">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 mb-1">Tinggi (%)</label>
                                    <input type="number" step="0.5" min="2" max="150" name="height" x-model="nodeModal.data.height" required
                                           class="w-full text-xs rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 py-1.5 px-2 text-gray-900 dark:text-white font-mono">
                                </div>
                            </div>
                            <input type="hidden" name="pos_x" :value="nodeModal.data.pos_x" x-show="!nodeModal.showCoords">
                            <input type="hidden" name="pos_y" :value="nodeModal.data.pos_y" x-show="!nodeModal.showCoords">
                            <input type="hidden" name="width" :value="nodeModal.data.width" x-show="!nodeModal.showCoords">
                            <input type="hidden" name="height" :value="nodeModal.data.height" x-show="!nodeModal.showCoords">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">Deskripsi & Catatan Stasiun</label>
                            <textarea name="description" x-model="nodeModal.data.description" rows="2" placeholder="Catatan fungsi stasiun, penanggung jawab, atau spesifikasi teknis..."
                                      class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"></textarea>
                        </div>
                    </div>

                    <div class="px-5 py-3.5 sm:px-6 sm:py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/80 dark:bg-slate-850 flex items-center justify-between flex-shrink-0">
                        <div>
                            <template x-if="nodeModal.isEdit">
                                <button type="button" @click="deleteNode(nodeModal.data.id)"
                                        class="inline-flex items-center gap-1.5 text-rose-600 hover:text-rose-700 dark:text-rose-400 text-xs font-semibold hover:underline">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span>Hapus Stasiun</span>
                                </button>
                            </template>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button"
                                    @click="nodeModal.open = false"
                                    class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-slate-300 hover:bg-gray-200/60 dark:hover:bg-slate-800 rounded-xl transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-95 rounded-xl shadow-md shadow-blue-500/25 transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span x-text="nodeModal.isEdit ? 'Simpan Perubahan' : 'Tambahkan ke Denah'"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="deleteModal.open"
             x-cloak
             class="fixed inset-0 z-[10000] ws-modal-overlay overflow-y-auto"
             style="z-index: 99999 !important;"
             role="dialog"
             aria-modal="true"
             @keydown.escape.window="deleteModal.open = false">

            <div x-show="deleteModal.open"
                 x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="deleteModal.open = false"
                 class="fixed inset-0 bg-gray-950/70 backdrop-blur-sm"></div>

            <div class="min-h-screen flex items-center justify-center p-4">
                <div x-show="deleteModal.open"
                     x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     @click.stop
                     class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-800 max-w-sm w-full p-6 text-center">

                    <div class="w-14 h-14 rounded-2xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>

                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Hapus Stasiun?</h3>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1.5">
                        Stasiun <strong class="text-gray-900 dark:text-white" x-text="'&quot;' + deleteModal.nodeName + '&quot;'"></strong>
                        akan dihapus permanen dari denah. Tindakan ini tidak dapat dibatalkan.
                    </p>

                    <div class="flex items-center gap-2.5 mt-5">
                        <button type="button"
                                @click="deleteModal.open = false"
                                class="flex-1 px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-xs font-bold text-gray-700 dark:text-slate-200 transition">
                            Batal
                        </button>
                        <button type="button"
                                @click="confirmDelete()"
                                class="flex-1 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-500/20 transition">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="zoneModal.open"
             x-cloak
             class="fixed inset-0 z-[9999] ws-modal-overlay overflow-y-auto"
             style="z-index: 99999 !important;"
             role="dialog"
             aria-modal="true"
             @keydown.escape.window="zoneModal.open = false">

            <div x-show="zoneModal.open"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="zoneModal.open = false"
                 class="fixed inset-0 bg-gray-950/60 backdrop-blur-sm transition-opacity"></div>

            <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 text-center">
                <div x-show="zoneModal.open"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2 sm:translate-y-0"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-2 sm:translate-y-0"
                     @click.stop
                     class="relative transform text-left bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-800 max-w-lg w-full flex flex-col max-h-[85vh] my-auto overflow-hidden">

                    <div class="px-5 py-3.5 sm:px-6 sm:py-4 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between flex-shrink-0 bg-gray-50/70 dark:bg-slate-850">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-white shadow-sm flex-shrink-0"
                                 :class="zoneModal.isEdit ? 'bg-indigo-600' : 'bg-blue-600'">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white"
                                    x-text="zoneModal.isEdit ? 'Edit Batas Zona Bengkel' : 'Tambah Zona Bengkel Baru'"></h3>
                                <p class="text-[11px] text-gray-500 dark:text-slate-400">Atur area spasial denah dan tema visual zona</p>
                            </div>
                        </div>
                        <button type="button" @click="zoneModal.open = false" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form :action="zoneModal.isEdit ? '{{ url('/admin/workshop-layout/zones') }}/' + zoneModal.data.id : '{{ route('admin.workshop.zones.store') }}'"
                          method="POST"
                          class="flex flex-col flex-1 overflow-hidden">
                        @csrf
                        <input type="hidden" name="_method" :value="zoneModal.isEdit ? 'PUT' : 'POST'">

                        <div class="p-5 sm:p-6 overflow-y-auto space-y-4 flex-1">

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">
                                    Nama Zona <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="name" x-model="zoneModal.data.name" required
                                       placeholder="Contoh: Zona F: Area Robotika & CNC"
                                       class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">
                                        Kode / Slug Unik <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="code" x-model="zoneModal.data.code" required
                                           placeholder="contoh: robotics_lab"
                                           class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-mono uppercase">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">
                                        Warna Garis & Batas <span class="text-rose-500">*</span>
                                    </label>
                                    <select name="color" x-model="zoneModal.data.color" required
                                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        <option value="blue">Biru (Machining/Default)</option>
                                        <option value="emerald">Hijau (Tool Crib/Storage)</option>
                                        <option value="cyan">Cyan (Perakitan/Assembly)</option>
                                        <option value="rose">Merah (Safety/K3)</option>
                                        <option value="amber">Kuning / Amber (Inspeksi)</option>
                                        <option value="purple">Ungu (Fabrikasi Lanjut)</option>
                                        <option value="indigo">Indigo (Otomasi / CNC)</option>
                                        <option value="slate">Abu-abu (Logistik)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="p-3.5 rounded-xl bg-gray-50/80 dark:bg-slate-800/60 border border-gray-200/60 dark:border-slate-700/60 space-y-2.5">
                                <div class="text-[11px] font-bold text-gray-700 dark:text-slate-300 flex items-center justify-between">
                                    <span>Koordinat & Ukuran Area Denah (%)</span>
                                    <span class="text-[10px] text-gray-400">Area Bebas Leluasa</span>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                    <div>
                                        <label class="block text-[10px] font-medium text-gray-500 mb-0.5">Pos X (%)</label>
                                        <input type="number" step="1" min="0" max="200" name="pos_x" x-model="zoneModal.data.pos_x" required
                                               class="w-full text-xs rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 py-1.5 px-2 text-gray-900 dark:text-white">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-medium text-gray-500 mb-0.5">Pos Y (%)</label>
                                        <input type="number" step="1" min="0" max="200" name="pos_y" x-model="zoneModal.data.pos_y" required
                                               class="w-full text-xs rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 py-1.5 px-2 text-gray-900 dark:text-white">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-medium text-gray-500 mb-0.5">Lebar (%)</label>
                                        <input type="number" step="1" min="2" max="200" name="width" x-model="zoneModal.data.width" required
                                               class="w-full text-xs rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 py-1.5 px-2 text-gray-900 dark:text-white">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-medium text-gray-500 mb-0.5">Tinggi (%)</label>
                                        <input type="number" step="1" min="2" max="200" name="height" x-model="zoneModal.data.height" required
                                               class="w-full text-xs rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 py-1.5 px-2 text-gray-900 dark:text-white">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">Deskripsi / Catatan Zona</label>
                                <textarea name="description" x-model="zoneModal.data.description" rows="2" placeholder="Catatan peruntukan zona, SOP khusus, atau kapasitas..."
                                          class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"></textarea>
                            </div>
                        </div>

                        <div class="px-5 py-3.5 sm:px-6 sm:py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/80 dark:bg-slate-850 flex items-center justify-between flex-shrink-0">
                            <div>
                                <template x-if="zoneModal.isEdit">
                                    <button type="button" @click="openDeleteZoneModal(zoneModal.data); zoneModal.open = false"
                                            class="inline-flex items-center gap-1.5 text-rose-600 hover:text-rose-700 dark:text-rose-400 text-xs font-semibold hover:underline">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Hapus Zona</span>
                                    </button>
                                </template>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button" @click="zoneModal.open = false"
                                        class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-slate-300 hover:bg-gray-200/60 dark:hover:bg-slate-800 rounded-xl transition">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 rounded-xl shadow-md shadow-indigo-500/25 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span x-text="zoneModal.isEdit ? 'Simpan Perubahan' : 'Buat Zona'"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="deleteZoneModal.open"
             x-cloak
             class="fixed inset-0 z-[10000] ws-modal-overlay overflow-y-auto"
             style="z-index: 99999 !important;"
             role="dialog"
             aria-modal="true"
             @keydown.escape.window="deleteZoneModal.open = false">

            <div x-show="deleteZoneModal.open"
                 x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="deleteZoneModal.open = false"
                 class="fixed inset-0 bg-gray-950/70 backdrop-blur-sm"></div>

            <div class="min-h-screen flex items-center justify-center p-4">
                <div x-show="deleteZoneModal.open"
                     x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     @click.stop
                     class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-800 max-w-sm w-full p-6 text-center">

                    <div class="w-14 h-14 rounded-2xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>

                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Hapus Zona Bengkel?</h3>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1.5">
                        Zona <strong class="text-gray-900 dark:text-white" x-text="'&quot;' + deleteZoneModal.zoneName + '&quot;'"></strong>
                        akan dihapus permanen. Garis batas area ini tidak akan tampil lagi di denah.
                    </p>

                    <div class="flex items-center gap-2.5 mt-5">
                        <button type="button"
                                @click="deleteZoneModal.open = false"
                                class="flex-1 px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-xs font-bold text-gray-700 dark:text-slate-200 transition">
                            Batal
                        </button>
                        <button type="button"
                                @click="confirmDeleteZone()"
                                class="flex-1 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-500/20 transition">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

</div>
@endsection
