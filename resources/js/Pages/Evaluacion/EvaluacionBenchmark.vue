<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modalv2 from "@/Components/v2/modal-v2.vue";
import Layout from '@/Layouts/Layout2.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import Chart from 'primevue/chart';
import { format } from 'date-fns';
import Bar from './Chart/Bar.vue';
import Radar from "./Chart/Radar.vue";
import Scatter from "./Chart/Scatter.vue";



// Variable para controlar qué modal está activo
const activeModal = ref(null);

// Función para abrir un modal
const openModal = (modalId) => {
    activeModal.value = modalId;
};

// Función para cerrar el modal
const closeModal = () => {
    activeModal.value = null;
};

const props = defineProps({
    evaluacion: Object,

});

const title = "assessment";
const subTitle = "evaluaciones";
const evaluacionClass = ref();
const showInfo = ref(false);

onMounted(() => {
})

const formatearFecha = (dateString) => {
    return format(new Date(dateString), 'dd/MM/yyyy');
};

function formatNumber(value) {
    if (value == null) return ''; // Manejar el caso cuando el valor es nulo o indefinido
    return parseFloat(value).toFixed(2);
}

function setClass(nota) {
    if (nota >= 0 && nota < 21) {
        return evaluacionClass.value = 'px-1 content-center';
    } else if (nota >= 21 && nota < 41) {
        return evaluacionClass.value = 'px-1 content-center';
    } else if (nota >= 41 && nota < 61) {
        return evaluacionClass.value = 'px-1 content-center';
    } else if (nota >= 61 && nota < 81) {
        return evaluacionClass.value = 'px-1 content-center';
    } else if (nota >= 81) {
        return evaluacionClass.value = 'px-1 content-center';
    }
}
</script>

<template>
    <Layout :titulo="title" :subTitulo="subTitle">

        <Head title="Assessment" />
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3 class="font-semibold text-xl">Resultados de Autoevaluación</h3>
            </div>

            <div class="flex items-center gap-2 my-3 breadcrumbs">
                <Link :href="route('evaluacion.index')" class="px-1">
                <h3>Evaluaciones</h3>
                </Link>
                <i class="pi-angle-right mx-2 pi" style="font-size: 1rem"></i>
                <Link href="#" class="active">
                <b>Benchmark</b>
                </Link>
            </div>
        </div>

        <div class="py-2 contentEvaluation">
            <div class="bg-white overflow-hidden">
                <div class="border-gray-200 bg-white">

                    <!-- Btn group -->
                    <!-- <div>
                        <button v-if="!showInfo"
                            class="hover:bg-slate-300 w-full text-center text-indigo-500 hover:text-white"
                            @click="showInfo = !showInfo">Mostrar
                            información
                        </button>

                        <button v-if="showInfo"
                            class="hover:bg-slate-300 w-full text-center text-indigo-500 hover:text-white"
                            @click="showInfo = !showInfo">Mostrar
                            menos
                        </button>
                    </div> -->

                    <!-- fecha and col cards -->
                    <div class="my-4 font-semibold text-gray-600">
                        Fecha: {{ formatearFecha(evaluacion.created_at) }}
                    </div>
                    <div class="gap-4 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 mb-8">
                        <!-- Card score -->
                        <div class="bg-white p-3 border rounded">
                            <div class="text-gray-800">
                                Score:
                                <div class="font-extrabold text-3xl" :class="setClass(evaluacion.global)">{{
                                    formatNumber(evaluacion.global) }}
                                    %
                                </div>
                                <div v-if="evaluacion.global >= 0 && evaluacion.global < 21"
                                    :class="setClass(evaluacion.global)">
                                    INICIAL
                                </div>
                                <div v-if="evaluacion.global >= 21 && evaluacion.global < 41"
                                    :class="setClass(evaluacion.global)">
                                    BÁSICO
                                </div>
                            </div>

                            <div v-if="evaluacion.global >= 41 && evaluacion.global < 61"
                                :class="setClass(evaluacion.global)">
                                INTERMEDIO
                            </div>
                            <div v-if="evaluacion.global >= 61 && evaluacion.global < 81"
                                :class="setClass(evaluacion.global)">
                                AVANZADO
                            </div>
                            <div v-if="evaluacion.global >= 81" :class="setClass(evaluacion.global)">LÍDER</div>
                        </div>

                        <!-- Card people -->
                        <div class="bg-white p-3 border rounded text-[#64758B]">
                            <div class="text-[#64758B]">People</div>
                            <div class="font-extrabold text-[#64758B] text-3xl" :class="setClass(evaluacion.gente)">{{
                                formatNumber(evaluacion.gente) }}
                                %
                            </div>
                            <div v-if="evaluacion.gente >= 0 && evaluacion.gente < 21"
                                :class="setClass(evaluacion.gente)">
                                INICIAL
                            </div>
                            <div v-if="evaluacion.gente >= 21 && evaluacion.gente < 41"
                                :class="setClass(evaluacion.gente)">
                                BÁSICO
                            </div>
                            <div v-if="evaluacion.gente >= 41 && evaluacion.gente < 61"
                                :class="setClass(evaluacion.gente)">
                                INTERMEDIO
                            </div>
                            <div v-if="evaluacion.gente >= 61 && evaluacion.gente < 81"
                                :class="setClass(evaluacion.gente)">
                                AVANZADO
                            </div>
                            <div v-if="evaluacion.gente >= 81" :class="setClass(evaluacion.gente)">LÍDER</div>
                        </div>

                        <!-- Card process -->
                        <div class="bg-white p-3 border rounded text-[#3C82F6]">
                            <div class="text-[#3C82F6]">Process & Systems</div>
                            <div class="font-extrabold text-[#3C82F6] text-3xl" :class="setClass(evaluacion.proceso)">{{
                                formatNumber(evaluacion.proceso) }}%</div>
                            <div v-if="evaluacion.proceso >= 0 && evaluacion.proceso < 21"
                                :class="setClass(evaluacion.proceso)">
                                INICIAL
                            </div>
                            <div v-if="evaluacion.proceso >= 21 && evaluacion.proceso < 41"
                                :class="setClass(evaluacion.proceso)">
                                BÁSICO
                            </div>
                            <div v-if="evaluacion.proceso >= 41 && evaluacion.proceso < 61"
                                :class="setClass(evaluacion.proceso)">
                                INTERMEDIO
                            </div>
                            <div v-if="evaluacion.proceso >= 61 && evaluacion.proceso < 81"
                                :class="setClass(evaluacion.proceso)">
                                AVANZADO
                            </div>
                            <div v-if="evaluacion.proceso >= 81" :class="setClass(evaluacion.proceso)">LÍDER</div>
                        </div>
                    </div>
                    <!-- colums charts -->
                    <div class="gap-5 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2">

                        <!-- colum lo que te duele -->
                        <div class="p-2 border rounded h-[920px] overflow-scroll">
                            <div>
                                <div class="flex justify-between items-center gap-3 my-3 p-3">
                                    <div class="font-semibold text-gray-800">LO QUE TE DUELE</div>
                                    <i @click="openModal('modal1')"
                                        class="hover:bg-gray-100 p-3 rounded-full cursor-pointer pi pi-info-circle"
                                        style="font-size: 1.5rem"></i>
                                </div>
                                <Bar :evaluacion="evaluacion" />
                                <!-- <div v-if="showInfo">
                                    <p class="p-1 text-justify text-xs">
                                        Primero se presentan los resultados de la evaluación del sistema de gestión, ya
                                        que
                                        este
                                        es un componente crítico para la excelencia operacional. De hecho, gran parte de
                                        tu
                                        calificación general depende de la puntuación en este elemento, ya que es
                                        fundamental
                                        para desplegar todo el potencial de los demás elementos. A continuación, se
                                        muestran
                                        los
                                        elementos de mejora continua, seguidos del resto de los pilares hasta llegar a
                                        la
                                        base:
                                        el desarrollo y el aprendizaje.
                                    </p>
                                    <p class="p-1 text-justify text-xs">
                                        Si tus resultados se encuentran debajo del 40% es un nivel esperado en la mayor
                                        parte de
                                        empresas, si estás entre 40%-75% felicidades seguramente ya comenzaste con un
                                        programa
                                        pero aún hace falta reforzar algunas prácticas, si estas por encima del 75% ¡es
                                        increíble! estás con prácticas maduras y estás listo para involucrar nuevos
                                        conceptos
                                        para el nivel 2 de iPMS® o de cualquier modelo de excelencia operacional.
                                    </p>
                                    <p class="p-1 text-justify text-xs">
                                        Como notarás, todos los elementos evaluados son esenciales; todas las empresas y
                                        todos
                                        los equipos, independientemente de su tipo, los requieren. Este es el fundamento
                                        sobre
                                        el que se edifican las empresas. Como gerentes o líderes de organizaciones,
                                        nuestra
                                        responsabilidad es fomentar estos elementos para asegurar nuestro desarrollo.
                                    </p>
                                    <p class="p-1 text-justify text-xs">
                                        Nuestras recomendaciones generales son:
                                    <ol class="list-decimal list-inside">
                                        <li>Establece un sistema de gestión realmente integrado.</li>
                                        <li>Capacita y certifica a tu personal</li>
                                        <li>Asegurate de generar estándares basados en plataformas tecnológicas, es el
                                            futuro.
                                        </li>
                                    </ol>
                                    </p>
                                    <p class="p-1 text-justify text-xs">
                                        Te invitamos a contactar a Productivity Center® e invertir 20 minutos de tu
                                        tiempo
                                        para
                                        que te expliquemos de manera detallada el informe completo de tu autoevaluación
                                        el
                                        cual
                                        comprende 40 hojas. Te proporcionaremos consejos específicos para cada uno de
                                        los 25
                                        puntos e incluso algunos cursos gratuitos en nuestra plataforma de aprendizaje.
                                        Escribenos a contacto@productivity.center para solicitarlo.
                                    </p>
                                </div> -->
                            </div>
                        </div>

                        <!-- col a gran nivel -->
                        <div>
                            <div class="mb-5 p-2 border rounded h-[450px] max-h-[600px] overflow-scroll">
                                <div class="flex justify-between items-center gap-3 my-3 p-3">
                                    <div class="font-semibold text-gray-800">A GRAN NIVEL</div>
                                    <i @click="openModal('modal2')"
                                        class="hover:bg-gray-100 p-3 rounded-full cursor-pointer pi pi-info-circle"
                                        style="font-size: 1.5rem">
                                    </i>
                                </div>

                                <Radar :evaluacion="evaluacion" />
                                <br>
                                <!-- <div v-if="showInfo" class="flex grid-cols-2 p-1 border rounded">
                                    <div class="w-1/2">
                                        <p class="bg-red-800 p-1 text-justify text-white text-xs">
                                            INICAL.- La oportunidad es grande para mejorar, todo lo que hagas con un
                                            sistema
                                            de
                                            excelencia operacional dará un gran beneficio en tu organización.
                                        </p>
                                        <p class="bg-red-500 p-1 text-justify text-white text-xs">
                                            BASICO:Haz establecido los primeros pasos en el trabajo con mejora contina,
                                            estableciendo procesos y midiendo resultados, Ajustar tu sistema de gestión
                                            te
                                            vendría bien en este punto
                                        </p>
                                        <p class="bg-yellow-500 p-1 text-justify text-white text-xs">
                                            INTERMEDIO : Un nivel ideal para obtener buenos resultados de un sistema de
                                            gestión operado con herramientas de mejora continua como Lean o Six Sigma.
                                        </p>
                                        <p class="bg-green-500 p-1 text-justify text-white text-xs">
                                            AVANZADO :Momento de dar un brinco a la tecnología, si tus procesos ya son
                                            estables
                                            será el momento idóneo para comenzar a evaluar el fase 2 de un sistema de
                                            exclencia
                                            operacional
                                        </p>
                                        <p class="bg-blue-500 p-1 text-justify text-white text-xs">
                                            LÍDER : Eres un líder en tu ramo, usando todo tu potencial y listo para
                                            enfrentar
                                            los retos, evaluate en fase 2 de excelencia operacional, mejora de forma
                                            integrada
                                        </p>

                                    </div>
                                    <div class="w-1/2">
                                        <p class="p-1 text-justify text-xs">
                                            Nuestros niveles de calificación máxima representan las prácticas de
                                            excelencia
                                            adoptadas por miles de empresas en todo el mundo durante la Fase 1 de
                                            Excelencia
                                            Operacional. Esto indica que estas prácticas son el estándar mínimo que se
                                            debe
                                            lograr para garantizar que tu equipo pueda aumentar su productividad,
                                            entregar
                                            resultados de alta calidad y contribuir de manera más significativa al éxito
                                            global
                                            de la organización.
                                        </p>
                                    </div>
                                </div> -->
                            </div>

                            <!-- Col en donde estas ? -->
                            <div class="p-2 border h-[450px] max-h-[600px] overflow-scroll rouded">
                                <div>
                                    <div class="flex justify-between items-center gap-3 my-3 p-3">
                                        <div class="font-semibold text-gray-800">DONDE ESTÁS</div>
                                        <i @click="openModal('modal3')"
                                            class="hover:bg-gray-100 p-3 rounded-full cursor-pointer pi pi-info-circle"
                                            style="font-size: 1.5rem">
                                        </i>
                                    </div>

                                    <Scatter :evaluacion="evaluacion" />
                                    <br>
                                    <!-- <div v-if="showInfo" class="p-1 border rounded">
                                        <p class="p-1 text-justify text-xs">
                                            Este mapa te permite conocer tu nivel de madurez en prácticas de excelencia
                                            operacional
                                            respecto a otras empresas que han desarrollado el ejercicio.
                                        </p>
                                        <p class="p-1 text-justify text-xs">
                                            Los colores te demuestran el nivel general:
                                        <ul>
                                            <li class="text-red-900">INICIAL.- Los procesos son inconsistentes y
                                                reactivos.</li>
                                            <li class="text-red-500">BÁSICO.- Los procesos clave están documentados y
                                                estandarizados, cultura aceptable.
                                            </li>
                                            <li class="text-yellow-500">INTERMEDIO.- Los procesos están optimizados y
                                                son
                                                impulsados
                                                por datos.</li>
                                            <li class="text-green-500">AVANZADO.- Monitoreo sistemático y mejora de las
                                                métricas
                                                clave.</li>
                                            <li class="text-blue-500">LÍDER.- Innovación continua y rendimiento líder en
                                                la
                                                industria.</li>
                                        </ul>
                                        </p>
                                        <p class="p-1 text-justify text-xs">
                                            Las zonas te advierten 3 posibles comportamientos:
                                        <ul>
                                            <li><strong>Zona A.-</strong> Procesos sólidos, los resultados se logran
                                                mediante
                                                procesos automatizados, pero en la mayoría de los casos con poco trabajo
                                                en
                                                equipo o
                                                un ambiente de equipo mejorable, alto riesgo de rotación y renuncias en
                                                la zona
                                                roja, posible zona de confort en el sector amarillo.
                                            </li>
                                            <li><strong>Zona B.-</strong> En camino hacia la mejora continua,
                                                organización
                                                equilibrada, uso y mejora de procesos alineados con el nivel de cultura.
                                                En esta
                                                zona, las posibilidades de lograr buenos avances en la excelencia
                                                operacional
                                                son
                                                altas.
                                            </li>
                                            <li><strong>Zona C.-</strong> Resultados a través de equipos con alta
                                                pasión, en la
                                                mayoría de los casos con poco seguimiento de procesos, organización más
                                                reactiva
                                                que
                                                planificada.
                                            </li>
                                        </ul>
                                        </p>
                                        <p class="p-1 text-justify text-xs">
                                            La mejora basada en la autoevaluación que recién has realizado se convierte
                                            en un
                                            instrumento esencial para el crecimiento y desarrollo de tu organización.
                                            Cada
                                            resultado
                                            obtenido puede brindarte una perspectiva y reflexión clara sobre qué áreas
                                            requieren
                                            mejora. Sin embargo, la efectividad de las mejoras se potencia cuando estas
                                            se
                                            ejecutan
                                            dentro de un sistema integrado, en lugar de tratarse como proyectos
                                            independientes.
                                        </p>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal LO QUE TE DUELE  -->
                <Modalv2 class="modalEvaluation" :isOpen="activeModal === 'modal1'" title="Lo que te duele"
                    @close="closeModal">
                    <!-- Contenido dinámico del modal -->
                    <div class="h-[600px] overflow-scroll">
                        <p class="text-[0.9rem]">
                            Primero se presentan los resultados de la evaluación del sistema de gestión, ya
                            que
                            este
                            es un componente crítico para la excelencia operacional. De hecho, gran parte de
                            tu
                            calificación general depende de la puntuación en este elemento, ya que es
                            fundamental
                            para desplegar todo el potencial de los demás elementos. A continuación, se
                            muestran
                            los
                            elementos de mejora continua, seguidos del resto de los pilares hasta llegar a
                            la
                            base:
                            el desarrollo y el aprendizaje.
                        </p>
                        <p class="my-5 text-[0.9rem]">
                            Si tus resultados se encuentran debajo del 40% es un nivel esperado en la mayor
                            parte de
                            empresas, si estás entre 40%-75% felicidades seguramente ya comenzaste con un
                            programa
                            pero aún hace falta reforzar algunas prácticas, si estas por encima del 75% ¡es
                            increíble! estás con prácticas maduras y estás listo para involucrar nuevos
                            conceptos
                            para el nivel 2 de iPMS® o de cualquier modelo de excelencia operacional.
                        </p>
                        <p class="my-5 text-[0.9rem]">
                            Como notarás, todos los elementos evaluados son esenciales; todas las empresas y
                            todos
                            los equipos, independientemente de su tipo, los requieren. Este es el fundamento
                            sobre
                            el que se edifican las empresas. Como gerentes o líderes de organizaciones,
                            nuestra
                            responsabilidad es fomentar estos elementos para asegurar nuestro desarrollo.
                        </p>
                        <p class="my-5 text-[0.9rem]">
                            Nuestras recomendaciones generales son:
                        <ol class="list-decimal list-inside">
                            <li>Establece un sistema de gestión realmente integrado.</li>
                            <li>Capacita y certifica a tu personal</li>
                            <li>Asegurate de generar estándares basados en plataformas tecnológicas, es el
                                futuro.
                            </li>
                        </ol>
                        </p>
                        <p class="my-5 text-[0.9rem]">
                            Te invitamos a contactar a Productivity Center® e invertir 20 minutos de tu
                            tiempo
                            para
                            que te expliquemos de manera detallada el informe completo de tu autoevaluación
                            el
                            cual
                            comprende 40 hojas. Te proporcionaremos consejos específicos para cada uno de
                            los 25
                            puntos e incluso algunos cursos gratuitos en nuestra plataforma de aprendizaje.
                            Escribenos a contacto@productivity.center para solicitarlo.
                        </p>
                    </div>
                </Modalv2>

                <!-- Modal Agran nivel -->
                <Modalv2 class="modalEvaluation" :isOpen="activeModal === 'modal2'" title="A gran Nivel"
                    @close="closeModal">
                    <!-- Contenido dinámico del modal -->
                    <div class="h-[600px] overflow-scroll">
                        <div>
                            <div>
                                <p class="bg-red-500 mb-3 p-3 rounded text-[0.9rem] text-white">
                                    <b>INICAL</b> <hr/>La oportunidad es grande para mejorar, todo lo que hagas con un
                                    sistema
                                    de
                                    excelencia operacional dará un gran beneficio en tu organización.
                                </p>
                                <p class="bg-red-400 my-5 p-3 rounded text-[0.9rem] text-white">
                                    <b>BASICO</b> <hr/>Haz establecido los primeros pasos en el trabajo con mejora contina,
                                    estableciendo procesos y midiendo resultados, Ajustar tu sistema de gestión
                                    te
                                    vendría bien en este punto
                                </p>
                                <p class="bg-yellow-400 my-5 p-3 rounded text-[0.9rem] text-black">
                                    <b>INTERMEDIO</b> <hr/>Un nivel ideal para obtener buenos resultados de un sistema de
                                    gestión operado con herramientas de mejora continua como Lean o Six Sigma.
                                </p>
                                <p class="bg-green-400 my-5 p-3 rounded text-[0.9rem] text-black">
                                    <b>AVANZADO</b> <hr/>Momento de dar un brinco a la tecnología, si tus procesos ya son
                                    estables
                                    será el momento idóneo para comenzar a evaluar el fase 2 de un sistema de
                                    exclencia
                                    operacional
                                </p>
                                <p class="bg-blue-400 my-5 p-3 rounded text-[0.9rem] text-white">
                                    <b>LÍDER</b> <hr/> Eres un líder en tu ramo, usando todo tu potencial y listo para
                                    enfrentar
                                    los retos, evaluate en fase 2 de excelencia operacional, mejora de forma
                                    integrada
                                </p>

                            </div>
                            <div>
                                <p class="text-[0.9rem]">
                                    Nuestros niveles de calificación máxima representan las prácticas de
                                    excelencia
                                    adoptadas por miles de empresas en todo el mundo durante la Fase 1 de
                                    Excelencia
                                    Operacional. Esto indica que estas prácticas son el estándar mínimo que se
                                    debe
                                    lograr para garantizar que tu equipo pueda aumentar su productividad,
                                    entregar
                                    resultados de alta calidad y contribuir de manera más significativa al éxito
                                    global
                                    de la organización.
                                </p>
                            </div>
                        </div>
                    </div>
                </Modalv2>

                <!-- Modal EN DONDE ESTÁS -->
                <Modalv2 class="modalEvaluation" :isOpen="activeModal === 'modal3'" title="Donde estas"
                    @close="closeModal">
                    <!-- Contenido dinámico del modal -->
                    <div class="h-[600px] overflow-scroll">
                        <div>
                            <p class="text-[0.9rem]">
                                Este mapa te permite conocer tu nivel de madurez en prácticas de excelencia
                                operacional
                                respecto a otras empresas que han desarrollado el ejercicio.
                            </p>

                            <p class="my-5 font-semibold"> Los colores te demuestran el nivel general:</p>
                            <div class="text-[0.9rem]">
                                <ul>
                                    <li class="my-5 font-medium text-red-900">INICIAL.- Los procesos son inconsistentes
                                        y
                                        reactivos.</li>
                                    <li class="my-5 font-medium text-red-500">BÁSICO.- Los procesos clave están
                                        documentados y
                                        estandarizados, cultura aceptable.
                                    </li>
                                    <li class="my-5 font-medium text-yellow-600">INTERMEDIO.- Los procesos están
                                        optimizados y
                                        son
                                        impulsados
                                        por datos.</li>
                                    <li class="my-5 font-medium text-green-900">AVANZADO.- Monitoreo sistemático y
                                        mejora de las
                                        métricas
                                        clave.</li>
                                    <li class="my-5 font-medium text-blue-900">LÍDER.- Innovación continua y rendimiento
                                        líder en
                                        la
                                        industria.</li>
                                </ul>
                            </div>
                            <p> Las zonas te advierten 3 posibles comportamientos:</p>
                            <div class="text-[0.9rem]">
                                <ul>
                                    <li class="my-6"><strong>Zona A.</strong> <br />Procesos sólidos, los resultados se
                                        logran
                                        mediante
                                        procesos automatizados, pero en la mayoría de los casos con poco trabajo
                                        en
                                        equipo o
                                        un ambiente de equipo mejorable, alto riesgo de rotación y renuncias en
                                        la zona
                                        roja, posible zona de confort en el sector amarillo.
                                    </li>
                                    <li class="my-6"><strong>Zona B.</strong> <br />En camino hacia la mejora continua,
                                        organización
                                        equilibrada, uso y mejora de procesos alineados con el nivel de cultura.
                                        En esta
                                        zona, las posibilidades de lograr buenos avances en la excelencia
                                        operacional
                                        son
                                        altas.
                                    </li>
                                    <li class="my-6"><strong>Zona C.</strong> <br />Resultados a través de equipos con
                                        alta
                                        pasión, en la
                                        mayoría de los casos con poco seguimiento de procesos, organización más
                                        reactiva
                                        que
                                        planificada.
                                    </li>
                                </ul>
                            </div>
                            <p class="text-[0.9rem]">
                                La mejora basada en la autoevaluación que recién has realizado se convierte
                                en un
                                instrumento esencial para el crecimiento y desarrollo de tu organización.
                                Cada
                                resultado
                                obtenido puede brindarte una perspectiva y reflexión clara sobre qué áreas
                                requieren
                                mejora. Sin embargo, la efectividad de las mejoras se potencia cuando estas
                                se
                                ejecutan
                                dentro de un sistema integrado, en lugar de tratarse como proyectos
                                independientes.
                            </p>
                        </div>
                    </div>
                </Modalv2>
            </div>
        </div>
    </Layout>
</template>
