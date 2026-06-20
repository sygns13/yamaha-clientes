<template>
    <Head title="Dashboard" />
    <AdminLayout title="Dashboard">

        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div v-for="card in statCards" :key="card.label"
                class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
                <div :class="['w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0', card.bgClass]">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <!-- Clientes -->
                        <path v-if="card.icon === 'users'"
                            stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <!-- Motos -->
                        <path v-else-if="card.icon === 'moto'"
                            stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        <!-- VIN texto -->
                        <path v-else-if="card.icon === 'text'"
                            stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        <!-- VIN foto -->
                        <path v-else
                            stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ card.value }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ card.label }}</p>
                </div>
            </div>
        </div>

        <!-- Gráficas fila 1: donut + barras horizontales -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

            <!-- Donut: VIN-1 vs VIN-2 -->
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">VIN-1 vs VIN-2</h2>
                <ApexChart
                    v-if="stats.total_motos > 0"
                    type="donut" height="260"
                    :options="chartVinOptions"
                    :series="chartVinSeries"
                />
                <div v-else class="h-64 flex items-center justify-center text-gray-400 text-sm">
                    Sin datos aún
                </div>
            </div>

            <!-- Barras horizontales: Top modelos -->
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Modelos más registrados</h2>
                <ApexChart
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

        <!-- Gráfica fila 2: área por mes -->
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Registros por mes (últimos 12 meses)</h2>
            <ApexChart
                type="area" height="240"
                :options="chartMesOptions"
                :series="chartMesSeries"
            />
        </div>

    </AdminLayout>
</template>

<script setup>
import { computed }      from 'vue';
import { Head }          from '@inertiajs/vue3';
import VueApexCharts     from 'vue3-apexcharts';
import AdminLayout       from '@/Layouts/AdminLayout.vue';

const ApexChart = VueApexCharts;

const props = defineProps({
    stats: Object,
});

// ── Tarjetas de estadísticas ────────────────────────────────────────────────
const statCards = computed(() => [
    { label: 'Total Clientes',   value: props.stats.total_clientes, bgClass: 'bg-yamaha-blue text-white',  icon: 'users' },
    { label: 'Total Motos',      value: props.stats.total_motos,    bgClass: 'bg-indigo-600 text-white',   icon: 'moto'  },
    { label: 'Registros VIN-1',  value: props.stats.total_vin1,     bgClass: 'bg-green-600 text-white',    icon: 'text'  },
    { label: 'Registros VIN-2',  value: props.stats.total_vin2,     bgClass: 'bg-yamaha-red text-white',   icon: 'photo' },
]);

// ── Gráfica donut: VIN-1 vs VIN-2 ──────────────────────────────────────────
const chartVinSeries = computed(() => [
    props.stats.total_vin1,
    props.stats.total_vin2,
]);
const chartVinOptions = {
    labels:      ['VIN-1 (Texto)', 'VIN-2 (Foto)'],
    colors:      ['#003087', '#E60012'],
    legend:      { position: 'bottom' },
    dataLabels:  { style: { fontSize: '13px' } },
    plotOptions: { pie: { donut: { size: '60%' } } },
    chart:       { toolbar: { show: false } },
};

// ── Gráfica barras: Top modelos ─────────────────────────────────────────────
const chartModelosSeries = computed(() => [{
    name: 'Registros',
    data: props.stats.modelos_top.map(m => m.motocicletas_count),
}]);
const chartModelosOptions = computed(() => ({
    colors:      ['#003087'],
    chart:       { toolbar: { show: false } },
    plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
    dataLabels:  { enabled: true, style: { fontSize: '11px' } },
    xaxis:       { categories: props.stats.modelos_top.map(m => m.nombre) },
    grid:        { borderColor: '#f1f5f9' },
}));

// ── Gráfica área: registros por mes ────────────────────────────────────────
const chartMesSeries = computed(() => [{
    name: 'Registros',
    data: props.stats.por_mes.map(m => m.total),
}]);
const chartMesOptions = computed(() => ({
    colors:     ['#E60012'],
    chart:      { toolbar: { show: false }, zoom: { enabled: false } },
    fill:       { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
    stroke:     { curve: 'smooth', width: 2 },
    dataLabels: { enabled: false },
    xaxis:      { categories: props.stats.por_mes.map(m => m.mes), labels: { style: { fontSize: '11px' } } },
    grid:       { borderColor: '#f1f5f9' },
    tooltip:    { y: { formatter: (v) => `${v} registros` } },
}));
</script>
