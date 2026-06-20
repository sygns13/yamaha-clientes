<template>
    <Head title="Dashboard" />
    <AdminLayout title="Dashboard">

        <!-- Stats cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <StatCard label="Total Clientes" :value="stats.total_clientes" color="blue"   icon="users"/>
            <StatCard label="Total Motos"    :value="stats.total_motos"    color="indigo" icon="moto"/>
            <StatCard label="Registros VIN-1" :value="stats.total_vin1"   color="green"  icon="text"/>
            <StatCard label="Registros VIN-2" :value="stats.total_vin2"   color="red"    icon="photo"/>
        </div>

        <!-- Charts row 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

            <!-- Donut VIN1 vs VIN2 -->
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">VIN-1 vs VIN-2</h2>
                <apexchart
                    v-if="stats.total_motos > 0"
                    type="donut" height="260"
                    :options="chartVinOptions"
                    :series="chartVinSeries"
                />
                <div v-else class="h-64 flex items-center justify-center text-gray-400 text-sm">
                    Sin datos aún
                </div>
            </div>

            <!-- Horizontal bar — Top modelos -->
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Modelos más registrados</h2>
                <apexchart
                    v-if="stats.modelos_top.length"
                    type="bar" height="260"
                    :options="chartModelosOptions"
                    :series="chartModelosSeries"
                />
                <div v-else class="h-64 flex items-center justify-center text-gray-400 text-sm">
                    Sin datos aún
                </div>
            </div>
        </div>

        <!-- Chart row 2 — Area por mes -->
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Registros por mes (últimos 12 meses)</h2>
            <apexchart
                type="area" height="240"
                :options="chartMesOptions"
                :series="chartMesSeries"
            />
        </div>

    </AdminLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head }     from '@inertiajs/vue3';
import VueApexCharts from 'vue3-apexcharts';
import AdminLayout  from '@/Layouts/AdminLayout.vue';

// Registrar apexchart globalmente en este componente
const apexchart = VueApexCharts;

const props = defineProps({ stats: Object });

// ── VIN donut ───────────────────────────────────────────────────────────────
const chartVinSeries  = computed(() => [props.stats.total_vin1, props.stats.total_vin2]);
const chartVinOptions = {
    labels:  ['VIN-1 (Texto)', 'VIN-2 (Foto)'],
    colors:  ['#003087', '#E60012'],
    legend:  { position: 'bottom' },
    dataLabels: { style: { fontSize: '13px' } },
    plotOptions: { pie: { donut: { size: '60%' } } },
    chart: { toolbar: { show: false } },
};

// ── Top modelos bar ─────────────────────────────────────────────────────────
const chartModelosSeries  = computed(() => [{
    name: 'Registros',
    data: props.stats.modelos_top.map(m => m.motocicletas_count),
}]);
const chartModelosOptions = computed(() => ({
    colors: ['#003087'],
    chart:  { toolbar: { show: false } },
    plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
    dataLabels:  { enabled: true, style: { fontSize: '11px' } },
    xaxis: { categories: props.stats.modelos_top.map(m => m.nombre) },
    grid:  { borderColor: '#f1f5f9' },
}));

// ── Por mes area ────────────────────────────────────────────────────────────
const chartMesSeries  = computed(() => [{
    name: 'Registros',
    data: props.stats.por_mes.map(m => m.total),
}]);
const chartMesOptions = computed(() => ({
    colors: ['#E60012'],
    chart:  { toolbar: { show: false }, zoom: { enabled: false } },
    fill:   { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
    stroke: { curve: 'smooth', width: 2 },
    dataLabels: { enabled: false },
    xaxis:  { categories: props.stats.por_mes.map(m => m.mes), labels: { style: { fontSize: '11px' } } },
    grid:   { borderColor: '#f1f5f9' },
    tooltip: { y: { formatter: (v) => `${v} registros` } },
}));
</script>

<!-- StatCard inline -->
<script>
const colorMap = {
    blue:   'bg-yamaha-blue text-white',
    indigo: 'bg-indigo-600 text-white',
    green:  'bg-green-600 text-white',
    red:    'bg-yamaha-red text-white',
};

export default {
    components: {
        StatCard: {
            props: ['label','value','color','icon'],
            computed: {
                cls() { return colorMap[this.color] || colorMap.blue; }
            },
            template: `
                <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
                    <div :class="['w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0', cls]">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path v-if="icon==='users'" stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path v-else-if="icon==='moto'" stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            <path v-else-if="icon==='text'" stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            <path v-else stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ value }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ label }}</p>
                    </div>
                </div>
            `,
        },
    },
};
</script>
