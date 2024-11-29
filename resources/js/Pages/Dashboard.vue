<script setup>
    import Layout2 from "@/Components/compv2/layout2.vue";
    import { Link } from "@inertiajs/vue3";
    import BtnUi from '@/Components/compv2/btnUi.vue';
    import AutoEvaluacion from "@/Components/compv2/autoEvaluacion.vue";
    import Image from 'primevue/image';
import Tabv2 from "@/Components/compv2/tabv2.vue";

</script>

<template>
    <Layout2>
        <div class="p-9">
            <!-- title + btn -->
            <div class="flex justify-between items-center">
                <Tabv2>
                    <!-- Tab pillars -->
                    <template #metroMap>
                        <div class="mt-8 rounded-md p-10 border-solid border-[1px] border-gray-200">                
                            <h1 class="text-xl text-center mb-7 font-bold">Metro Map</h1>
                            <div class="p-[100px] bg-[url('/branch_background.svg')] bg-cover bg-no-repeat bg-center"  style="background-position: -70px 0px;">
                                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                                    <!-- Gente y cultura -->

                                    <Link :href="route('formMetroMap')">
                                        <div class="shadow-md flex items-center justify-center p-7 w-24 h-24  transition ease-in-out delay-0 hover:-translate-y-1 hover:scale-110 bg-white border hover:bg-orange-400 cursor-pointer border-gray-200 rounded-full">
                                            <svg id="Group_132" data-name="Group 132" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="52.799" height="80.091" viewBox="0 0 52.799 80.091">
                                                <defs>
                                                    <clipPath id="clip-path">
                                                    <rect id="Rectangle_64" data-name="Rectangle 64" width="532.799" height="80.091" fill="#555658"/>
                                                    </clipPath>
                                                </defs>
                                                <g id="Group_132-2" data-name="Group 132" clip-path="url(#clip-path)">
                                                    <path id="Path_604" data-name="Path 604" d="M3.413,204.631a9.371,9.371,0,0,1,9.011-6.8H26.395l13.164,6.582,9.76-3.9,3.48,8.7-9.76,3.9a9.372,9.372,0,0,1-7.671-.319l-.7-.351-2.506,10.022,4.086,2.335a9.372,9.372,0,0,1,4.442,10.409L35.975,254.06H26.316L31.6,232.94,23.649,228.4,17.233,254.06H7.574l11.714-46.855H12.424l-2.677,9.371H0Z" transform="translate(0 -173.969)" fill="#494a4b"/>
                                                    <path id="Path_605" data-name="Path 605" d="M185.791,21.59l-1.05,2.452m-1.75,4.087.016.007m-5.095,2.816L176.22,34.9a3.557,3.557,0,1,0,6.538,2.8l1.691-3.949m6.242-23.605-.7,1.635m9.258,7.13-1.651.661M178.444,10l.661,1.651m-7.791,7.607-1.635-.7m29.423,12.6-1.635-.7m-4.9-2.1a8.891,8.891,0,1,1-4.672-11.674A8.891,8.891,0,0,1,192.564,28.361Z" transform="translate(-149.211 -8.794)" fill="none" stroke="#494a4b" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/>
                                                    <path id="Path_606" data-name="Path 606" d="M218.127,250.309l2.258,4.943,4.943-2.258a2.413,2.413,0,0,0,1.258-1.351l.038-.1a2.3,2.3,0,0,0-3.342-2.783l-.612.366-.223-.677a2.3,2.3,0,0,0-4.348-.085l-.038.1a2.412,2.412,0,0,0,.066,1.845" transform="translate(-191.623 -217.084)" fill="#fff"/>
                                                </g>
                                            </svg>      
                                        </div>
                                    </Link>
                                    <!-- T.I -->
                                    <div class="shadow-md flex items-center justify-center p-7 w-24 h-24 transition ease-in-out delay-0 hover:-translate-y-1 hover:scale-110 bg-white border hover:bg-blue-400 cursor-pointer border-gray-200 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="57.477" height="43.107" viewBox="0 0 57.477 43.107">
                                            <g id="wifi-svgrepo-com" transform="translate(0 -3)">
                                                <path id="Path_607" data-name="Path 607" d="M0,17.369l4.209-4.209a34.69,34.69,0,0,1,49.059,0l4.209,4.209-5.08,5.08-4.209-4.209a27.506,27.506,0,0,0-38.9,0L5.08,22.449Z" fill="#494a4b"/>
                                                <path id="Path_608" data-name="Path 608" d="M7.909,22.241l-5.08-5.08,4.209-4.209a20.321,20.321,0,0,1,28.738,0l4.209,4.209-5.08,5.08L30.7,18.032a13.136,13.136,0,0,0-18.578,0Z" transform="translate(7.332 10.369)" fill="#494a4b"/>
                                                <path id="Path_609" data-name="Path 609" d="M14.074,25.369,5.657,16.952l4.209-4.209a5.952,5.952,0,0,1,8.417,0l4.208,4.209Z" transform="translate(14.664 20.738)" fill="#494a4b"/>
                                            </g>
                                        </svg>
                                    </div>
                                    <!-- Ventas -->
                                    <div class="shadow-md flex items-center justify-center p-7 w-24 h-24 transition ease-in-out delay-0 hover:-translate-y-1 hover:scale-110 bg-white border hover:bg-red-400 cursor-pointer border-gray-200 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="60.501" height="51.911" viewBox="0 0 60.501 51.911">
                                            <g id="sales-amount-svgrepo-com" transform="translate(-0.001 -71.68)">
                                                <path id="Path_610" data-name="Path 610" d="M8.09,152.756a.6.6,0,0,0,.6-.6V135.344a.6.6,0,0,0-.6-.6H3.251a.6.6,0,0,0-.6.6v16.813a.6.6,0,0,0,.6.6Zm0,2.393H3.251a3.008,3.008,0,0,1-3.024-2.991V135.344a3.008,3.008,0,0,1,3.024-2.991H8.09a3.008,3.008,0,0,1,3.024,2.991v16.814A3.008,3.008,0,0,1,8.09,155.148Zm16.444-2.393a.6.6,0,0,0,.6-.6V131.872a.6.6,0,0,0-.6-.6H19.7a.6.6,0,0,0-.6.6v20.285a.6.6,0,0,0,.6.6Zm0,2.393H19.7a3.008,3.008,0,0,1-3.024-2.991V131.872a3.008,3.008,0,0,1,3.024-2.991h4.839a3.008,3.008,0,0,1,3.024,2.991v20.286A3.008,3.008,0,0,1,24.534,155.148Zm16.444-2.357a.6.6,0,0,0,.6-.6V128.435a.6.6,0,0,0-.6-.6H36.14a.6.6,0,0,0-.6.6v23.758a.6.6,0,0,0,.6.6Zm0,2.393H36.14a3.008,3.008,0,0,1-3.024-2.991V128.435a3.008,3.008,0,0,1,3.024-2.991h4.839A3.008,3.008,0,0,1,44,128.435v23.758A3.008,3.008,0,0,1,40.979,155.184Zm16.444-2.393a.6.6,0,0,0,.6-.6V125.445a.6.6,0,0,0-.6-.6H52.585a.6.6,0,0,0-.6.6v26.749a.6.6,0,0,0,.6.6Zm0,2.393H52.585a3.008,3.008,0,0,1-3.024-2.991V125.445a3.008,3.008,0,0,1,3.024-2.991h4.839a3.008,3.008,0,0,1,3.024,2.991v26.749A3.008,3.008,0,0,1,57.423,155.185ZM57.057,106a130.679,130.679,0,0,1-55.919,16.29,1.2,1.2,0,1,0,.146,2.388,133.119,133.119,0,0,0,56.96-16.593,1.189,1.189,0,0,0,.46-1.63A1.217,1.217,0,0,0,57.057,106Z" transform="translate(0 -31.594)" fill="#494a4b"/>
                                                <path id="Path_611" data-name="Path 611" d="M882.3,74.766l7.715.012a1.543,1.543,0,1,0,0-3.086l-7.715-.012a1.543,1.543,0,0,0,0,3.086Z" transform="translate(-831.061)" fill="#494a4b"/>
                                                <path id="Path_612" data-name="Path 612" d="M982.867,73.371l-.023,7.673a1.543,1.543,0,1,0,3.086.009l.023-7.673a1.543,1.543,0,1,0-3.086-.009Z" transform="translate(-925.452 -0.141)" fill="#494a4b"/>
                                            </g>
                                        </svg>
                
                                    </div>
                                    <!-- Operaciones -->
                                    <div class="shadow-md flex items-center justify-center p-7 w-24 h-24 transition ease-in-out delay-0 hover:-translate-y-1 hover:scale-110 bg-white border hover:bg-green-400 cursor-pointer border-gray-200 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60">
                                            <g id="operations-record-svgrepo-com" transform="translate(0.123 0.123)">
                                                <rect id="Rectangle_65" data-name="Rectangle 65" width="19" height="4" transform="translate(36.877 36.877)" fill="#49494a"/>
                                                <rect id="Rectangle_66" data-name="Rectangle 66" width="19" height="4" transform="translate(36.877 44.877)" fill="#49494a"/>
                                                <rect id="Rectangle_67" data-name="Rectangle 67" width="19" height="4" transform="translate(36.877 51.877)" fill="#49494a"/>
                                                <path id="Path_613" data-name="Path 613" d="M21.2,28.664A7.3,7.3,0,0,1,13.733,21.2,7.3,7.3,0,0,1,21.2,13.733,7.3,7.3,0,0,1,28.664,21.2H32.4A11.2,11.2,0,1,0,21.2,32.4Z" transform="translate(8.679 8.679)" fill="#49494a"/>
                                                <path id="Path_614" data-name="Path 614" d="M52.591,18.88,48.185,11.25a3.729,3.729,0,0,0-4.429-1.67l-4.543,1.537A20.608,20.608,0,0,0,36.765,9.7L35.825,5a3.733,3.733,0,0,0-3.661-3H23.355a3.733,3.733,0,0,0-3.66,3l-.94,4.7a20.468,20.468,0,0,0-2.477,1.406L11.762,9.58a3.729,3.729,0,0,0-4.429,1.67L2.927,18.88A3.734,3.734,0,0,0,3.7,23.55l3.6,3.167c-.032.469-.071.936-.071,1.412s.019.957.052,1.429L3.7,32.709a3.734,3.734,0,0,0-.769,4.671l4.406,7.629a3.729,3.729,0,0,0,4.429,1.67L16.3,45.142a20.481,20.481,0,0,0,2.449,1.415l.94,4.7a3.733,3.733,0,0,0,3.661,3h4.4V50.526h-4.4L22.029,43.9A16.976,16.976,0,0,1,17,40.967l-6.433,2.176-4.4-7.629,5.085-4.47a16.66,16.66,0,0,1-.013-5.838L6.16,20.746l4.407-7.629,6.4,2.163a16.855,16.855,0,0,1,5.066-2.92l1.326-6.627h8.809l1.325,6.627a16.98,16.98,0,0,1,5.03,2.933l6.433-2.176,4.4,7.629-5.222,4.577L46.6,28.13l5.226-4.579a3.734,3.734,0,0,0,.77-4.671Z" transform="translate(2.118 1.747)" fill="#49494a"/>
                                                <rect id="_Transparent_Rectangle_" data-name="&lt;Transparent Rectangle&gt;" width="60" height="60" transform="translate(-0.123 -0.123)" fill="none"/>
                                            </g>
                                        </svg>
                
                                    </div>
                                    <!-- Administración -->
                                    <div class="shadow-md flex items-center justify-center p-7 w-24 h-24 transition ease-in-out delay-0 hover:-translate-y-1 hover:scale-110 bg-white border hover:bg-gray-400 cursor-pointer border-gray-200 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="61.156" height="71.286" viewBox="0 0 61.156 71.286">
                                            <defs>
                                                <clipPath id="clip-path">
                                                <rect id="Rectangle_68" data-name="Rectangle 68" width="61.156" height="71.286" transform="translate(0 0)" fill="none"/>
                                                </clipPath>
                                            </defs>
                                            <g id="Group_133" data-name="Group 133" transform="translate(0 -0.004)">
                                                <g id="Group_133-2" data-name="Group 133" transform="translate(0 0.004)" clip-path="url(#clip-path)">
                                                <path id="Path_615" data-name="Path 615" d="M61.141,17.476a65.828,65.828,0,0,1-7.064,30.048A57.084,57.084,0,0,1,31.3,71.061a1.324,1.324,0,0,1-1.514-.018,57.236,57.236,0,0,1-26.9-33.915A69.16,69.16,0,0,1,.034,14.889c.028-.937.16-1.871.175-2.808.013-.8.355-1.025,1.132-1.047A47.823,47.823,0,0,0,17.109,8.117,37.861,37.861,0,0,0,29.9.373c.454-.428.77-.534,1.289-.054,5.911,5.46,12.982,8.514,20.817,9.965a56.212,56.212,0,0,0,7.817.756c.808.035,1.068.359,1.111,1.1.1,1.777.272,3.55.207,5.337m-2.512-.069c0-1,.01-2.006,0-3.009-.01-.758-.4-1.076-1.171-1.07a30.716,30.716,0,0,1-5.495-.59A43.89,43.89,0,0,1,31.231,3.586c-.592-.484-.94-.272-1.416.1A45.029,45.029,0,0,1,3.934,13.314c-.958.059-1.465.355-1.453,1.345,0,.259-.024.518-.029.776a67.388,67.388,0,0,0,2.83,21.177A54.639,54.639,0,0,0,29.857,68.235a1.177,1.177,0,0,0,1.411.016A54.892,54.892,0,0,0,48.5,52.106c7.089-10.474,10.051-22.177,10.126-34.7" transform="translate(0 -0.003)" fill="#49494a"/>
                                                <path id="Path_616" data-name="Path 616" d="M471.431,274.026a5.664,5.664,0,0,1,1.593.053.949.949,0,0,1,.907,1.035c-.036,1.51-.093,3.019-.159,4.528-.024.541-.016.585.521.641a17.845,17.845,0,0,1,3.751.817,2.522,2.522,0,0,1,1.91,2.415,20.213,20.213,0,0,1,.128,2.815,2.207,2.207,0,0,1-.04.3c-.137.66-.419.862-1.056.656-.845-.273-1.666-.622-2.508-.907a12.567,12.567,0,0,0-5.63-.65,6.885,6.885,0,0,0-2.744.851,3.322,3.322,0,0,0-1.5,3.409,4.075,4.075,0,0,0,2.5,3,20.057,20.057,0,0,0,3.7,1.3,32.2,32.2,0,0,1,5.523,1.966,8.371,8.371,0,0,1,4.668,5.4,10.18,10.18,0,0,1-.915,8.141,10.378,10.378,0,0,1-4.418,3.974A15.817,15.817,0,0,1,473,315.252a.45.45,0,0,0-.433.511q-.043,2.132-.1,4.264c-.01.38-.037.76-.06,1.14a.741.741,0,0,1-.556.7,3.969,3.969,0,0,1-.694.2,8.843,8.843,0,0,1-2.843.088c-.735-.134-.938-.35-.928-1.1.02-1.383.081-2.766.125-4.148.006-.2-.006-.408.019-.609.069-.559.1-.65-.6-.721a17.383,17.383,0,0,1-4.593-1.043c-.224-.088-.439-.2-.661-.294a2.123,2.123,0,0,1-1.288-1.722,17.857,17.857,0,0,1-.214-3.113,2.241,2.241,0,0,1,.04-.34c.159-1,.7-1.316,1.623-.884a22.3,22.3,0,0,0,3.078,1.226,13.579,13.579,0,0,0,7.921.072,4.467,4.467,0,0,0,2.753-2.1,3.766,3.766,0,0,0-.94-4.681,11.814,11.814,0,0,0-3.829-1.74,50.316,50.316,0,0,1-5.388-1.782,9.508,9.508,0,0,1-4.988-4.279c-1.476-2.849-1.666-7.493.9-10.549a10.514,10.514,0,0,1,4.455-3.034,14.329,14.329,0,0,1,2.565-.718c.223-.04.324-.126.329-.371.035-1.624.082-3.248.13-4.872.02-.69.182-.887.836-1.093a8.152,8.152,0,0,1,1.774-.23" transform="translate(-440.035 -262.463)" fill="#49494a"/>
                                                </g>
                                            </g>
                                            </svg>
                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                <!-- Tab pillars -->
                <template #autoEval>
                    <AutoEvaluacion />
                </template>
            </Tabv2>
            </div>
        </div>
    </Layout2>
</template>