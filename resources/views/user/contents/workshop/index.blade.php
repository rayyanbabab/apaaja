@extends('user.layouts.dashboard-user')

@section('title', 'Peta Lokasi & Denah Bengkel (2D)')

@section('user')
<style>
[x-cloak] { display: none !important; }

.ws-user-canvas {
    height: 720px !important;
    min-height: 720px !important;
    width: 100%;
    position: relative;
    overflow: hidden;
    background-color: #f1f5f9;
    background-image:
        linear-gradient(rgba(203,213,225,0.7) 1px, transparent 1px),
        linear-gradient(90deg, rgba(203,213,225,0.7) 1px, transparent 1px),
        linear-gradient(rgba(226,232,240,0.4) 1px, transparent 1px),
        linear-gradient(90deg, rgba(226,232,240,0.4) 1px, transparent 1px);
    background-size: 100px 100px, 100px 100px, 20px 20px, 20px 20px;
}
html.dark .ws-user-canvas {
    background-color: #0b1120 !important;
    background-image:
        linear-gradient(rgba(30,41,59,0.65) 1px, transparent 1px),
        linear-gradient(90deg, rgba(30,41,59,0.65) 1px, transparent 1px),
        linear-gradient(rgba(51,65,85,0.3) 1px, transparent 1px),
        linear-gradient(90deg, rgba(51,65,85,0.3) 1px, transparent 1px) !important;
}

.zone-machining { border: 2px dashed rgba(59,130,246,0.4);  background: rgba(59,130,246,0.04); }
.zone-tool_crib { border: 2px dashed rgba(16,185,129,0.4);  background: rgba(16,185,129,0.04); }
.zone-assembly  { border: 2px dashed rgba(6,182,212,0.4);   background: rgba(6,182,212,0.04);  }
.zone-safety    { border: 2px dashed rgba(244,63,94,0.4);   background: rgba(244,63,94,0.04);  }
.zone-logistics { border: 2px dashed rgba(100,116,139,0.4); background: rgba(100,116,139,0.04);}

.zone-color-blue    { border: 2px dashed rgba(59,130,246,0.4);  background: rgba(59,130,246,0.04); }
.zone-color-emerald { border: 2px dashed rgba(16,185,129,0.4);  background: rgba(16,185,129,0.04); }
.zone-color-cyan    { border: 2px dashed rgba(6,182,212,0.4);   background: rgba(6,182,212,0.04);  }
.zone-color-rose    { border: 2px dashed rgba(244,63,94,0.4);   background: rgba(244,63,94,0.04);  }
.zone-color-slate   { border: 2px dashed rgba(100,116,139,0.4); background: rgba(100,116,139,0.04);}
.zone-color-amber   { border: 2px dashed rgba(245,158,11,0.4);  background: rgba(245,158,11,0.04); }
.zone-color-purple  { border: 2px dashed rgba(168,85,247,0.4);  background: rgba(168,85,247,0.04); }
.zone-color-indigo  { border: 2px dashed rgba(99,102,241,0.4);  background: rgba(99,102,241,0.04); }

@keyframes radar-pulse {
    0%   { transform: scale(0.95); opacity: 1; }
    50%  { transform: scale(1.35); opacity: 0.5; }
    100% { transform: scale(1.6);  opacity: 0; }
}
.animate-radar-user { animation: radar-pulse 1.5s cubic-bezier(0,0,0.2,1) infinite; }

.ws-node-card { transition: box-shadow .15s ease, transform .15s ease; }
.ws-node-card:hover { transform: scale(1.025); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
</style>

<script>
function userWorkshopViewer(initialNodes, initialZones) {
    return {
        nodes: initialNodes || [],
        zones: initialZones || [],
        selectedNode: null,
        drawerOpen: false,
        searchQuery: '',
        zoomLevel: 1.0,
        panOffset: { x: 0, y: 0 },
        isPanning: false,
        panStart: { x: 0, y: 0 },
        isFullscreen: false,

        initComponent() {},

        get totalItems() {
            return this.nodes.reduce((s, n) => s + (n.telemetry?.total_items || 0), 0);
        },
        get readyItems() {
            return this.nodes.reduce((s, n) => s + (n.telemetry?.available_borrow_stock || 0), 0);
        },

        get filteredNodes() {
            if (!this.searchQuery.trim()) return this.nodes;
            const q = this.searchQuery.toLowerCase();
            return this.nodes.filter(node => {
                const matchName  = (node.name  || '').toLowerCase().includes(q);
                const matchCode  = (node.code  || '').toLowerCase().includes(q);
                const matchItems = (node.telemetry?.items || []).some(it =>
                    (it.nama || '').toLowerCase().includes(q) || (it.kode || '').toLowerCase().includes(q)
                );
                return matchName || matchCode || matchItems;
            });
        },

        isSpotlightNode(node) {
            if (!this.searchQuery.trim()) return false;
            const q = this.searchQuery.toLowerCase();
            return (node.name || '').toLowerCase().includes(q)
                || (node.code || '').toLowerCase().includes(q)
                || (node.telemetry?.items || []).some(it =>
                    (it.nama || '').toLowerCase().includes(q) || (it.kode || '').toLowerCase().includes(q)
                );
        },

        selectNode(node) { this.selectedNode = node; this.drawerOpen = true; },

        zoomIn()    { if (this.zoomLevel < 3.5) this.zoomLevel = +(this.zoomLevel + 0.15).toFixed(2); },
        zoomOut()   { if (this.zoomLevel > 0.25) this.zoomLevel = +(this.zoomLevel - 0.15).toFixed(2); },
        resetZoom() { this.zoomLevel = 1.0; this.panOffset = { x: 0, y: 0 }; },
        toggleFullscreen() { this.isFullscreen = !this.isFullscreen; },
        onWheel(e)  { if (e.deltaY < 0) this.zoomIn(); else this.zoomOut(); },

        getClientCoords(e) {
            if (e.touches && e.touches.length > 0) return { x: e.touches[0].clientX, y: e.touches[0].clientY };
            if (e.changedTouches && e.changedTouches.length > 0) return { x: e.changedTouches[0].clientX, y: e.changedTouches[0].clientY };
            return { x: e.clientX, y: e.clientY };
        },
        startPan(e) {
            if (e.target.closest('.ws-node-card') || e.target.closest('button')) return;
            const pt = this.getClientCoords(e);
            this.isPanning = true;
            this.panStart = { x: pt.x - this.panOffset.x, y: pt.y - this.panOffset.y };
        },
        onPan(e)  {
            if (this.isPanning) {
                const pt = this.getClientCoords(e);
                this.panOffset = { x: pt.x - this.panStart.x, y: pt.y - this.panStart.y };
            }
        },
        endPan()  { this.isPanning = false; },

        getZoneLabel(zone) {
            const zObj = (this.zones || []).find(z => z.code === zone);
            if (zObj) return zObj.name;
            const labels = {
                machining: 'Zona A: Machining & Fabrikasi',
                tool_crib: 'Zona B: Tool Crib & Rak Alat',
                assembly: 'Zona C: Perakitan & Solder',
                safety: 'Zona D: Kios APD & K3',
                logistics: 'Zona E: Dermaga Logistik'
            };
            return labels[zone] || zone || 'Area Bengkel';
        },

        getZoneBoxClass(zone) {
            const color = (typeof zone === 'object' && zone !== null) ? (zone.color || 'blue') : (zone || 'blue');
            return `zone-color-${color}`;
        },

        getZoneTextClass(zone) {
            const color = (typeof zone === 'object' && zone !== null) ? (zone.color || 'blue') : (zone || 'blue');
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

        getNodeCodeBadgeClass(node) {
            const m = { blue:'bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300', emerald:'bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300', cyan:'bg-cyan-100 dark:bg-cyan-900/60 text-cyan-700 dark:text-cyan-300', amber:'bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300', rose:'bg-rose-100 dark:bg-rose-900/60 text-rose-700 dark:text-rose-300', purple:'bg-purple-100 dark:bg-purple-900/60 text-purple-700 dark:text-purple-300', slate:'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' };
            return m[node.color_theme || 'blue'] || m['blue'];
        },
        getNodeBeaconClass(node) { return node.telemetry?.status_color?.badge || 'bg-emerald-500'; },
        getNodeIconBgClass(node) {
            const m = { blue:'bg-blue-600 text-white shadow-blue-500/30', emerald:'bg-emerald-600 text-white shadow-emerald-500/30', cyan:'bg-cyan-600 text-white shadow-cyan-500/30', amber:'bg-amber-600 text-white shadow-amber-500/30', rose:'bg-rose-600 text-white shadow-rose-500/30', purple:'bg-purple-600 text-white shadow-purple-500/30', slate:'bg-slate-700 text-white shadow-slate-500/30' };
            return m[node.color_theme || 'blue'] || m['blue'];
        },
        renderNodeIcon(iconType) {
            const icons = {
                lathe:    '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                rack:     '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>',
                workbench:'<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16M7 6v14M17 6v14"/></svg>',
                soldering:'<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                shield:   '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
                truck:    '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 17l4 4 4-4m-4-5v9m-7-7h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z"/></svg>',
            };
            icons.machine = icons.lathe; icons.cabinet = icons.rack; icons.safety_kiosk = icons.shield;
            icons.logistics_bay = icons.truck; icons.flame = icons.shield; icons.first_aid = icons.shield;
            return icons[iconType] || icons.rack;
        },
    };
}
</script>

<div class="space-y-4" x-data="userWorkshopViewer({{ Js::from($nodesJson) }}, {{ Js::from($zonesJson) }})" x-init="initComponent()">

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm">
        <div class="px-5 sm:px-7 py-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800/50">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                            Peta Digital Bengkel
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Denah Interaktif Bengkel</h1>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Cari lokasi fisik perkakas, cek ketersediaan, dan lakukan peminjaman langsung</p>
                </div>

                <div class="flex flex-col gap-3 sm:items-end w-full sm:w-auto">

                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-xs font-bold text-gray-700 dark:text-slate-300" x-text="nodes.length + ' Stasiun'"></span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400" x-text="readyItems + ' Siap Pinjam'"></span>
                        </div>
                    </div>

                    <div class="relative w-full sm:w-72">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text"
                               x-model="searchQuery"
                               placeholder="Cari alat, kode, atau rak…"
                               class="w-full pl-9 pr-8 py-2 text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <button type="button"
                                x-show="searchQuery"
                                @click="searchQuery = ''"
                                class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-slate-300">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-5 sm:px-7 py-2.5 border-t border-gray-100 dark:border-slate-800 bg-gray-50 dark:bg-slate-800/50 flex flex-wrap items-center gap-x-5 gap-y-2">
            <span class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Status Stasiun:</span>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5">
                <div class="flex items-center gap-1.5 text-[11px] font-medium text-gray-600 dark:text-slate-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>Siap Pinjam
                </div>
                <div class="flex items-center gap-1.5 text-[11px] font-medium text-gray-600 dark:text-slate-400">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>Dipinjam Penuh
                </div>
                <div class="flex items-center gap-1.5 text-[11px] font-medium text-gray-600 dark:text-slate-400">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>Stok ROP
                </div>
                <div class="flex items-center gap-1.5 text-[11px] font-medium text-gray-600 dark:text-slate-400">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>Kritis
                </div>
                <div class="flex items-center gap-1.5 text-[11px] font-medium text-gray-600 dark:text-slate-400">
                    <span class="w-2 h-2 rounded-full bg-violet-500"></span>Servis
                </div>
            </div>
            <span class="ml-auto text-[10px] text-gray-400 dark:text-slate-600 hidden sm:inline">Scroll/pinch untuk zoom · Drag untuk navigasi</span>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 shadow-sm overflow-hidden relative"
         :class="isFullscreen ? 'fixed inset-0 z-50 rounded-none' : ''">

        <div style="position:absolute;top:12px;right:12px;z-index:40"
             class="flex items-center gap-1 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md p-1.5 rounded-xl shadow-md border border-gray-200 dark:border-slate-700">
            <button type="button" @click="zoomIn()"
                    class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 transition-colors" title="Perbesar">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </button>
            <span class="text-[11px] font-black text-gray-600 dark:text-slate-400 px-1.5 min-w-[38px] text-center tabular-nums" x-text="Math.round(zoomLevel * 100) + '%'"></span>
            <button type="button" @click="zoomOut()"
                    class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 transition-colors" title="Perkecil">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            </button>
            <div class="h-4 w-px bg-gray-200 dark:bg-slate-700 mx-0.5"></div>
            <button type="button" @click="resetZoom()"
                    class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 transition-colors" title="Reset posisi">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
            </button>
            <div class="h-4 w-px bg-gray-200 dark:bg-slate-700 mx-0.5"></div>
            <button type="button" @click="toggleFullscreen()"
                    class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 transition-colors"
                    :title="isFullscreen ? 'Keluar Layar Penuh' : 'Layar Penuh'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/></svg>
            </button>
        </div>

        <div x-show="searchQuery"
             x-cloak
             style="position:absolute;top:12px;left:12px;z-index:40"
             class="flex items-center gap-1.5 bg-amber-500 text-white text-[11px] font-bold px-3 py-1.5 rounded-lg shadow-md">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <span x-text="filteredNodes.length + ' stasiun cocok'"></span>
        </div>

        <div class="w-full ws-user-canvas cursor-grab active:cursor-grabbing"
             style="height:720px;min-height:720px;position:relative;overflow:hidden;touch-action:none;"
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

            <div class="absolute inset-0 origin-center"
                 :style="`transform: translate(${panOffset.x}px, ${panOffset.y}px) scale(${zoomLevel}); width:100%; height:100%;`">

                <template x-for="z in zones" :key="z.id">
                    <div class="absolute rounded-2xl p-3 pointer-events-none transition-all"
                         :class="getZoneBoxClass(z)"
                         :style="`left:${z.pos_x}%;top:${z.pos_y}%;width:${z.width}%;height:${z.height}%`">
                        <div class="font-extrabold text-[10px] uppercase tracking-widest"
                             :class="getZoneTextClass(z)"
                             x-text="z.name"></div>
                    </div>
                </template>

                <div x-show="nodes.length === 0"
                     class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center pointer-events-none">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-slate-800 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-3 shadow-inner">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Denah Belum Dikonfigurasi</h3>
                    <p class="text-xs text-gray-500 dark:text-slate-400 max-w-sm mt-1">Tata letak stasiun bengkel sedang dalam proses pengaturan oleh instruktur/laboran.</p>
                </div>

                <template x-for="node in filteredNodes" :key="node.id">
                    <div class="ws-node-card absolute rounded-2xl cursor-pointer p-3 flex flex-col justify-between shadow-md border bg-white/95 dark:bg-slate-900/95 backdrop-blur-sm"
                         :class="[
                             selectedNode && selectedNode.id === node.id
                                 ? 'ring-2 ring-blue-500 border-blue-300 dark:border-blue-700 shadow-xl shadow-blue-500/10'
                                 : 'border-gray-200 dark:border-slate-700',
                             isSpotlightNode(node) ? 'ring-2 ring-amber-400 animate-bounce' : ''
                         ]"
                         :style="`left:${node.pos_x}%;top:${node.pos_y}%;width:${node.width}%;height:${node.height}%;`"
                         @click="selectNode(node)">

                        <div x-show="isSpotlightNode(node)"
                             class="absolute -inset-2 rounded-2xl bg-amber-400/25 animate-radar-user pointer-events-none"></div>

                        <div class="flex items-center justify-between gap-1 pointer-events-none">
                            <span class="text-[10px] font-black px-2 py-0.5 rounded-md uppercase tracking-wider"
                                  :class="getNodeCodeBadgeClass(node)"
                                  x-text="node.code"></span>
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="getNodeBeaconClass(node)"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5" :class="getNodeBeaconClass(node)"></span>
                            </span>
                        </div>

                        <div class="flex items-center gap-2 my-1 overflow-hidden pointer-events-none">
                            <div class="w-7 h-7 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md"
                                 :class="getNodeIconBgClass(node)"
                                 x-html="renderNodeIcon(node.icon || node.type)"></div>
                            <div class="overflow-hidden min-w-0">
                                <div class="text-xs font-bold leading-tight truncate text-gray-900 dark:text-white" x-text="node.name"></div>
                                <div class="text-[10px] text-gray-500 dark:text-slate-400 truncate" x-text="node.location_name || getZoneLabel(node.zone)"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-1.5 border-t border-gray-100 dark:border-slate-800 text-[10px] pointer-events-none">
                            <span class="text-gray-500 dark:text-slate-500 font-medium" x-text="(node.telemetry?.total_items || 0) + ' jenis'"></span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold" x-text="(node.telemetry?.available_borrow_stock || 0) + ' siap'"></span>
                        </div>
                    </div>
                </template>

                <template x-if="filteredNodes.length === 0">
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 pointer-events-none">
                        <div class="w-14 h-14 rounded-2xl bg-white dark:bg-slate-800 shadow-md border border-gray-200 dark:border-slate-700 flex items-center justify-center text-gray-300 dark:text-slate-600">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-bold text-gray-500 dark:text-slate-400">Tidak ditemukan</p>
                            <p class="text-xs text-gray-400 dark:text-slate-600 mt-0.5">Coba kata kunci yang berbeda</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="drawerOpen" x-cloak
             class="fixed inset-0 z-[9998] overflow-hidden"
             style="z-index: 99999 !important;"
             role="dialog" aria-modal="true">

            <div x-show="drawerOpen"
                 x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="drawerOpen = false"
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

            <div class="fixed inset-y-0 right-0 max-w-full flex pl-10" style="z-index: 99999 !important;">
                <div x-show="drawerOpen"
                     x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                     class="w-screen max-w-md bg-white dark:bg-slate-900 shadow-2xl flex flex-col h-full border-l border-gray-200 dark:border-slate-800">

                    <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 bg-gray-50 dark:bg-slate-800/60 flex-shrink-0">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-black uppercase tracking-wider bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300" x-text="selectedNode?.code"></span>
                                    <span class="text-xs text-gray-400 dark:text-slate-500" x-text="selectedNode ? getZoneLabel(selectedNode.zone) : ''"></span>
                                </div>
                                <h2 class="text-base font-bold text-gray-900 dark:text-white leading-snug" x-text="selectedNode?.name"></h2>
                                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5 line-clamp-2" x-text="selectedNode?.description || 'Stasiun kerja / rak inventaris bengkel.'"></p>
                            </div>
                            <button type="button" @click="drawerOpen = false"
                                    class="p-1.5 mt-0.5 text-gray-400 hover:text-gray-700 dark:hover:text-white rounded-lg transition-colors flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="flex items-center gap-3 mt-4">
                            <div class="flex-1 bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-700 p-3 text-center">
                                <div class="text-lg font-extrabold text-gray-900 dark:text-white" x-text="selectedNode?.telemetry?.items?.length || 0"></div>
                                <div class="text-[10px] text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wide mt-0.5">Jenis Alat</div>
                            </div>
                            <div class="flex-1 bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-700 p-3 text-center">
                                <div class="text-lg font-extrabold text-emerald-600 dark:text-emerald-400" x-text="selectedNode?.telemetry?.available_borrow_stock || 0"></div>
                                <div class="text-[10px] text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wide mt-0.5">Siap Pinjam</div>
                            </div>
                            <div class="flex-1 bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-700 p-3 text-center">
                                <div class="text-lg font-extrabold text-blue-600 dark:text-blue-400" x-text="selectedNode?.telemetry?.total_items || 0"></div>
                                <div class="text-[10px] text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wide mt-0.5">Total Item</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 min-h-0 overflow-y-auto">
                        <div class="p-5 space-y-3">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-slate-500">Alat di Stasiun Ini</h3>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400"
                                      x-text="(selectedNode?.telemetry?.items?.length || 0) + ' item'"></span>
                            </div>

                            <template x-for="item in selectedNode?.telemetry?.items || []" :key="item.id">
                                <div class="p-3.5 rounded-xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-800/60 flex items-center gap-3 hover:border-blue-200 dark:hover:border-blue-800 transition-colors">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs font-bold text-gray-900 dark:text-white truncate" x-text="item.nama"></div>
                                        <div class="flex items-center gap-1.5 mt-1">
                                            <span class="text-[10px] font-mono text-gray-400 dark:text-slate-500" x-text="item.kode"></span>
                                            <span class="text-gray-300 dark:text-slate-700">·</span>
                                            <span class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400" x-text="item.stok_peminjaman + ' siap pinjam'"></span>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <template x-if="item.stok_peminjaman > 0">
                                            <a :href="'{{ url('/user/borrowing/create') }}/' + item.id"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm shadow-blue-500/20 transition-all">
                                                Pinjam
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                        </template>
                                        <template x-if="item.stok_peminjaman <= 0">
                                            <span class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-slate-500 text-xs font-semibold">Habis</span>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!selectedNode?.telemetry?.items || selectedNode.telemetry.items.length === 0">
                                <div class="py-10 text-center">
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3 text-gray-300 dark:text-slate-600">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <p class="text-xs font-semibold text-gray-500 dark:text-slate-400">Belum Ada Perkakas</p>
                                    <p class="text-[11px] text-gray-400 dark:text-slate-600 mt-0.5">Tidak ada perkakas aktif di lokasi ini</p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="px-5 py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50 dark:bg-slate-800/60 flex items-center justify-between flex-shrink-0">
                        <a href="{{ route('user.borrowing.index') }}"
                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                            Buka Katalog Lengkap
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <button type="button" @click="drawerOpen = false"
                                class="px-4 py-2 bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-200 text-xs font-bold rounded-xl transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
