<template>
    <div class="grid grid-cols-4 bg-white gap-[100px]">
        <div class="xl:col-span-2 col-span-3">
            <!-- Chart -->
            <div class="relative aspect-square max-w-xl md:max-w-3xl mx-auto">
              <canvas ref="radarChart"></canvas>
            </div>

             <!-- Legend -->
            <div>
              <h2 class="text-2xl font-semibold text-gray-800">Comparativo entre Evaluaciones</h2>
              <div class="space-y-3">
                <div v-for="(item, index) in evaluationTypes" :key="index" class="flex items-center gap-3">
                  <div class="w-6 h-[2px]" :style="{ backgroundColor: item.color }"></div>
                  <span class="text-gray-700">{{ item.label }} ({{ item.percentage }}%)</span>
                </div>
              </div>
            </div>
        </div>

        <div class="xl:col-span-2 col-span-3 mt-5">
            <h2 class="text-2xl mb-3 font-semibold text-gray-800">Objetivos</h2>
            <div class="text-sm leading-10">
                <ul>
                  <li>- Gestión por procesos mediante PDCA.</li>
                  <li>- Ejecución de los procesos de verificacion y auditoria.</li>
                  <li>- Definir y arrancar le evaluación del desempeño del equipo.</li>
                  <li>- Actuar por principios de actuación.</li>
                  <li>- Sistematizar la gestion de procedimientos por medio de Odoo.</li>
                  <li>- Definir y administrar por presupuestos por departamento.</li>
                  <li>- Desarrollar el proceso comercial de nuestros 3 canales de venta.</li>
                </ul>
            </div>
        </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, onUnmounted } from 'vue'
  import { Chart, RadarController, RadialLinearScale, PointElement, LineElement, Tooltip } from 'chart.js'
  
  Chart.register(RadarController, RadialLinearScale, PointElement, LineElement, Tooltip)
  
  const evaluationTypes = [
    { label: 'Promedio Reportes directos', color: '#FF6384', percentage: 29 },
    { label: 'Promedio Pares', color: '#36A2EB', percentage: 31 },
    { label: 'Autoevaluación', color: '#FFCE56', percentage: 30 },
    { label: 'Evaluación Jefe', color: '#4BC0C0', percentage: 39 }
  ]
  
  const metrics = [
    'DNP/Ventas/MKT',
    'TI',
    'Administración',
    'Operaciones',
    'Gente y Cultura'
  ]
  
  const data = {
    reportesDirectos: [4, 3, 4, 3, 4],
    pares: [5, 4, 4, 3, 3],
    autoevaluacion: [4, 5, 4, 4, 4],
    jefe: [3, 4, 5, 4, 5]
  }
  
  const radarChart = ref(null)
  let chart = null
  
  onMounted(() => {
    const ctx = radarChart.value.getContext('2d')
    
    chart = new Chart(ctx, {
      type: 'radar',
      data: {
        labels: metrics,
        datasets: [
          {
            label: 'Reportes directos',
            data: data.reportesDirectos,
            borderColor: '#FF6384',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            borderWidth: 2,
            pointBackgroundColor: '#FF6384',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#FF6384'
          },
          {
            label: 'Pares',
            data: data.pares,
            borderColor: '#36A2EB',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            borderWidth: 2,
            pointBackgroundColor: '#36A2EB',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#36A2EB'
          },
          {
            label: 'Autoevaluación',
            data: data.autoevaluacion,
            borderColor: '#FFCE56',
            backgroundColor: 'rgba(255, 206, 86, 0.1)',
            borderWidth: 2,
            pointBackgroundColor: '#FFCE56',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#FFCE56'
          },
          {
            label: 'Jefe',
            data: data.jefe,
            borderColor: '#4BC0C0',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            borderWidth: 2,
            pointBackgroundColor: '#4BC0C0',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#4BC0C0'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          r: {
            min: 0,
            max: 5,
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              font: {
                size: 12
              }
            },
            pointLabels: {
              font: {
                size: 14
              }
            },
            grid: {
              color: 'rgba(0, 0, 0, 0.1)'
            },
            angleLines: {
              color: 'rgba(0, 0, 0, 0.1)'
            }
          }
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: 'white',
            titleColor: 'black',
            bodyColor: 'black',
            borderColor: 'rgba(0,0,0,0.1)',
            borderWidth: 1,
            padding: 10,
            displayColors: true,
            callbacks: {
              label: function(context) {
                return `${context.dataset.label}: ${context.raw}`;
              }
            }
          }
        }
      }
    })
  })
  
  onUnmounted(() => {
    if (chart) {
      chart.destroy()
    }
  })
  </script>