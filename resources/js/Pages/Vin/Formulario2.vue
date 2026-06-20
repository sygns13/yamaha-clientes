<template>
    <Head title="BETA - VIN" />
    <div class="min-h-screen bg-moto flex items-center justify-center p-4">
        <div class="w-full max-w-lg">

            <!-- Card -->
            <div class="bg-white/90 rounded-2xl shadow-2xl overflow-hidden">

                <!-- Header -->
                <div class="bg-yamaha-blue px-8 py-5 border-b-4 border-yamaha-red">
                    <div class="flex justify-center items-center mb-4 relative">
                        <img
                            src="/img/logoyamaha-fullwhitef.png"
                            alt="Mr. Lúpulo"
                            class="h-20 md:h-20 w-auto object-contain drop-shadow-lg scale-125 md:scale-100"
                            @error="onLogoError"
                        />
                        
                        <span class="absolute top-0 right-0 bg-yamaha-red text-white text-[0.63rem] font-bold px-2 py-0.5 rounded uppercase tracking-wider">
                            versión Beta
                        </span>
                    </div>
                    
                    <h1 class="text-white text-xl font-estricta font-bold">¡Bienvenido a la Familia Yamaha!</h1>
                    <p class="text-blue-200 font-estricta text-md mt-1">Completa tus datos para enterarte de nuestras novedades.</p>
                    <p class="text-red-400 font-estricta text-md font-semibold mt-1">*Formulario válido para registro hasta el 30 de junio del 2026</p>
                </div>

                <!-- Body -->
                <div class="px-8 py-4">

                    <!-- Éxito -->
                    <div v-if="showSuccess" class="text-center py-6">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-11 h-11 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2 font-estricta">¡Registro Exitoso!</h2>
                        <p class="text-gray-500 mb-1 font-estricta">Tu información se ha guardado correctamente.</p>
                        <p class="text-gray-500 mb-7 font-estricta">¡Pronto tendremos más noticias para contarte!</p>
                        <Link :href="route('vin')"
                            class="inline-block bg-yamaha-blue hover:bg-yamaha-blue-dark text-white font-semibold px-7 py-3 rounded-lg transition-colors">
                            Volver al inicio
                        </Link>
                    </div>

                    <!-- Formulario -->
                    <form v-else @submit.prevent="submit" novalidate class="space-y-3">

                        <!-- Error global -->
                        <div v-if="Object.keys(form.errors).length || Object.keys(localErrors).length"
                            class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-700 font-estricta">
                            Por favor corrige los errores indicados.
                        </div>

                        <!-- Nombres y Apellidos -->
                        <div>
                            <label class="block text-md font-semibold text-gray-700 mb-1 font-estricta">
                                Nombres y Apellidos <span class="text-yamaha-red">*</span>
                            </label>
                            <input
                                v-model="form.nombres_apellidos"
                                type="text"
                                placeholder="Ej: Juan Pérez García"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-md
                                       focus:border-yamaha-blue focus:ring-1 focus:ring-yamaha-blue
                                       transition-colors font-estricta"
                                :class="{'border-red-400 bg-red-50': form.errors.nombres_apellidos || localErrors.nombres_apellidos}"
                            />
                            <p v-if="form.errors.nombres_apellidos || localErrors.nombres_apellidos" class="mt-1 text-xs text-red-600">
                                {{ form.errors.nombres_apellidos || localErrors.nombres_apellidos }}
                            </p>
                        </div>

                        <!-- Celular -->
                        <div>
                            <label class="block text-md font-semibold text-gray-700 mb-1 font-estricta">
                                Celular <span class="text-yamaha-red">*</span>
                            </label>
                            <input
                                v-model="form.celular"
                                type="tel"
                                maxlength="9"
                                placeholder="Ej: 987654321"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-md
                                       focus:border-yamaha-blue focus:ring-1 focus:ring-yamaha-blue
                                       transition-colors font-estricta"
                                :class="{'border-red-400 bg-red-50': form.errors.celular || localErrors.celular}"
                            />
                            <p v-if="form.errors.celular || localErrors.celular" class="mt-1 text-xs text-red-600">
                                {{ form.errors.celular || localErrors.celular }}
                            </p>
                        </div>

                        <!-- Correo -->
                        <div>
                            <label class="block text-md font-estricta font-semibold text-gray-700 mb-1">
                                Correo electrónico <span class="text-yamaha-red">*</span>
                            </label>
                            <input
                                v-model="form.correo"
                                type="email"
                                placeholder="correo@ejemplo.com"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-md font-estricta
                                       focus:border-yamaha-blue focus:ring-1 focus:ring-yamaha-blue
                                       transition-colors"
                                :class="{'border-red-400 bg-red-50': form.errors.correo || localErrors.correo}"
                            />
                            <p v-if="form.errors.correo || localErrors.correo" class="mt-1 text-xs text-red-600">
                                {{ form.errors.correo || localErrors.correo }}
                            </p>
                        </div>

                        <!-- Modelo de Moto -->
                        <div>
                            <label class="block text-md font-estricta font-semibold text-gray-700 mb-1">
                                Modelo de Moto <span class="text-yamaha-red">*</span>
                            </label>
                            <select
                                v-model="form.modelo_moto_id"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-md font-estricta
                                       focus:border-yamaha-blue focus:ring-1 focus:ring-yamaha-blue
                                       transition-colors"
                                :class="{'border-red-400 bg-red-50': form.errors.modelo_moto_id || localErrors.modelo_moto_id}"
                            >
                                <option value="">— Seleccione un modelo —</option>
                                <option v-for="m in modelos" :key="m.id" :value="m.id">
                                    {{ m.nombre }}
                                </option>
                                <option value="otros">OTROS</option>
                            </select>
                            <p v-if="form.errors.modelo_moto_id || localErrors.modelo_moto_id" class="mt-1 text-xs text-red-600">
                                {{ form.errors.modelo_moto_id || localErrors.modelo_moto_id }}
                            </p>
                            <!-- Input libre cuando elige OTROS -->
                            <div v-if="esOtroModelo" class="mt-2">
                                <input
                                    v-model="form.modelo_moto_otro"
                                    type="text"
                                    placeholder="Escribe el modelo de tu moto"
                                    maxlength="100"
                                    class="w-full rounded-lg border-gray-300 shadow-sm text-md font-estricta
                                           focus:border-yamaha-blue focus:ring-1 focus:ring-yamaha-blue
                                           transition-colors"
                                    :class="{'border-red-400 bg-red-50': localErrors.modelo_moto_otro}"
                                />
                                <p v-if="localErrors.modelo_moto_otro" class="mt-1 text-xs text-red-600">
                                    {{ localErrors.modelo_moto_otro }}
                                </p>
                            </div>
                        </div>

                        <!-- Año de Modelo (year picker) -->
                        <div ref="yearPickerRef" class="relative">
                            <label class="block text-md font-estricta font-semibold text-gray-700 mb-1">
                                Año de Modelo <span class="text-yamaha-red">*</span>
                            </label>
                            <!-- Trigger -->
                            <button
                                type="button"
                                @click="yearPickerOpen = !yearPickerOpen"
                                class="w-full rounded-lg border border-gray-300 bg-white shadow-sm text-md font-estricta
                                       py-2.5 px-3.5 flex items-center justify-between
                                       focus:border-yamaha-blue focus:outline-none focus:ring-1 focus:ring-yamaha-blue
                                       transition-colors"
                                :class="{'border-red-400 bg-red-50': form.errors.anio_modelo || localErrors.anio_modelo}"
                            >
                                <span :class="form.anio_modelo ? 'text-gray-900' : 'text-gray-400'">
                                    {{ form.anio_modelo || '— Seleccione el año —' }}
                                </span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                     :class="{'rotate-180': yearPickerOpen}"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <!-- Grid de años -->
                            <div v-if="yearPickerOpen"
                                 class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-xl p-3">
                                <div class="grid grid-cols-4 gap-1.5 max-h-52 overflow-y-auto">
                                    <button
                                        v-for="anio in aniosDisponibles"
                                        :key="anio"
                                        type="button"
                                        @click="seleccionarAnio(anio)"
                                        class="rounded-lg py-2 text-sm font-estricta font-semibold transition-colors"
                                        :class="form.anio_modelo === String(anio)
                                            ? 'bg-yamaha-blue text-white'
                                            : 'text-gray-700 hover:bg-blue-50 hover:text-yamaha-blue'"
                                    >
                                        {{ anio }}
                                    </button>
                                </div>
                            </div>
                            <p v-if="form.errors.anio_modelo || localErrors.anio_modelo" class="mt-1 text-xs text-red-600">
                                {{ form.errors.anio_modelo || localErrors.anio_modelo }}
                            </p>
                        </div>

                        <!-- Código VIN (texto) -->
                        <div>
                            <label class="block text-md font-estricta font-semibold text-gray-700 mb-1">
                                Código VIN <span class="text-yamaha-red">*</span>
                            </label>
                            <input
                                v-model="form.vin_descripcion"
                                type="text"
                                placeholder="Ej: LBVPC4267K2000001"
                                maxlength="100"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-md font-estricta font-mono tracking-wide uppercase
                                       focus:border-yamaha-blue focus:ring-1 focus:ring-yamaha-blue
                                       transition-colors"
                                :class="{'border-red-400 bg-red-50': form.errors.vin_descripcion || localErrors.vin_descripcion}"
                            />
                            <p class="mt-1 text-sm text-gray-600 font-estricta">Escribe el código VIN tal como aparece en tu motocicleta.</p>
                            <p v-if="form.errors.vin_descripcion || localErrors.vin_descripcion" class="mt-1 text-xs text-red-600">
                                {{ form.errors.vin_descripcion || localErrors.vin_descripcion }}
                            </p>
                        </div>

                        <!-- Foto VIN (file upload) -->
                        <div>
                            <label class="block text-md font-estricta font-semibold text-gray-700 mb-1">
                                Foto del Código VIN <span class="text-yamaha-red">*</span>
                            </label>

                            <!-- Drop zone -->
                            <div
                                class="mt-1 border-2 border-dashed rounded-xl p-5 text-center transition-colors"
                                :class="previewUrl
                                    ? 'border-yamaha-blue bg-blue-50'
                                    : (form.errors.vin_imagen || localErrors.vin_imagen)
                                        ? 'border-red-400 bg-red-50'
                                        : 'border-gray-300 bg-gray-50'"
                                @dragover.prevent
                                @drop.prevent="onDrop"
                            >
                                <!-- Preview de imagen seleccionada -->
                                <div v-if="previewUrl" class="space-y-3">
                                    <img :src="previewUrl" alt="Vista previa VIN" class="max-h-40 mx-auto rounded-lg object-contain shadow"/>
                                    <p class="text-sm font-medium text-yamaha-blue truncate px-2 font-estricta">{{ form.vin_imagen?.name }}</p>
                                    <div class="flex flex-col sm:flex-row gap-2 justify-center">
                                        <button
                                            type="button"
                                            @click.stop="$refs.fileInput.click()"
                                            class="flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg border border-gray-300 text-gray-600 text-xs font-estricta hover:border-yamaha-blue hover:text-yamaha-blue transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                            </svg>
                                            Cambiar imagen
                                        </button>
                                        <button
                                            type="button"
                                            @click.stop="abrirCamara()"
                                            class="flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg border border-gray-300 text-gray-600 text-xs font-estricta hover:border-yamaha-blue hover:text-yamaha-blue transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
                                            </svg>
                                            Tomar nueva foto
                                        </button>
                                    </div>
                                </div>
                                <!-- Sin imagen -->
                                <div v-else class="space-y-4">
                                    <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                    </svg>

                                    <!-- Botones de acción -->
                                    <div class="flex flex-col sm:flex-row gap-2 justify-center">
                                        <!-- Galería / Archivo -->
                                        <button
                                            type="button"
                                            @click.stop="$refs.fileInput.click()"
                                            class="flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg border-2 border-yamaha-blue text-yamaha-blue font-semibold text-sm font-estricta hover:bg-yamaha-blue hover:text-white transition-colors"
                                        >
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                            </svg>
                                            Seleccionar imagen
                                        </button>

                                        <!-- Cámara -->
                                        <button
                                            type="button"
                                            @click.stop="abrirCamara()"
                                            class="flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-yamaha-blue text-white font-semibold text-sm font-estricta hover:bg-yamaha-blue-dark transition-colors"
                                        >
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
                                            </svg>
                                            Tomar fotografía
                                        </button>
                                    </div>

                                    <p class="text-xs text-gray-400 font-estricta">JPG, PNG, WEBP — máximo 5 MB</p>
                                    <p class="text-xs text-gray-300 hidden sm:block">o arrastra una imagen aquí</p>
                                </div>
                            </div>

                            <!-- Input galería (sin capture) -->
                            <input
                                ref="fileInput"
                                type="file"
                                accept="image/*"
                                class="hidden"
                                @change="onFileChange"
                            />
                            <!-- Input cámara (capture=environment → cámara trasera en Android e iOS) -->
                            <input
                                ref="cameraInput"
                                type="file"
                                accept="image/*"
                                capture="environment"
                                class="hidden"
                                @change="onFileChange"
                            />
                            <p v-if="form.errors.vin_imagen || localErrors.vin_imagen" class="mt-1 text-xs text-red-600">
                                {{ form.errors.vin_imagen || localErrors.vin_imagen }}
                            </p>
                        </div>

                        <!-- Términos y condiciones -->
                        <div class="rounded-lg border font-estricta text-sm leading-snug px-4 py-3 transition-colors"
                             :class="localErrors.terminos ? 'border-red-300 bg-red-50' : 'border-gray-200 bg-gray-50'">
                            <label class="flex items-start gap-3 cursor-pointer select-none">
                                <input
                                    v-model="aceptaTerminos"
                                    type="checkbox"
                                    class="mt-0.5 h-4 w-4 flex-shrink-0 rounded border-gray-300 text-yamaha-blue
                                           focus:ring-yamaha-blue focus:ring-1 cursor-pointer"
                                />
                                <span class="text-gray-700">
                                    Acepto los
                                    <a href="https://cloud.crm.yamaha-motor.com.pe/manual-garantia-terminos-condiciones"
                                       target="_blank" rel="noopener noreferrer"
                                       class="underline text-yamaha-blue hover:text-yamaha-blue-dark font-semibold">
                                        Términos y condiciones</a>,
                                    así como el tratamiento de mis datos personales bajo las condiciones de la declaración de las
                                    <a href="https://www.yamaha-motor.com.pe/politicas-privacidad"
                                       target="_blank" rel="noopener noreferrer"
                                       class="underline text-yamaha-blue hover:text-yamaha-blue-dark font-semibold">
                                        Políticas de Privacidad</a>
                                    de Yamaha Motor del Perú.
                                </span>
                            </label>
                            <p v-if="localErrors.terminos" class="mt-2 text-xs text-red-600">
                                {{ localErrors.terminos }}
                            </p>
                        </div>

                        <!-- Botón -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-yamaha-red hover:bg-yamaha-red-dark disabled:opacity-60 disabled:cursor-not-allowed
                                   text-white font-bold py-3 px-6 rounded-lg uppercase tracking-wider text-md
                                   transition-colors flex items-center justify-center gap-2 mt-2 font-estricta"
                        >
                            <svg v-if="form.processing" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                            {{ form.processing ? 'Enviando...' : 'Registrarme' }}
                        </button>

                        <!-- Link a VIN1 -->
                        <!-- <p class="text-center text-sm text-gray-600 mt-3 font-estricta">
                            ¿Prefieres escribir el código VIN?
                            <Link :href="route('vin1')" class="text-yamaha-blue hover:underline font-medium">
                                Usa el Formulario 1
                            </Link>
                        </p> -->

                    </form>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 border-t border-gray-100 px-8 py-3 flex items-center justify-between font-estricta">
                    <span class="text-sm text-gray-600">© {{ new Date().getFullYear() }} Yamaha Motor.</span>
                    <!-- <span class="text-sm font-semibold text-yamaha-red uppercase tracking-wider">VIN-2</span> -->
                </div>

            </div>
        </div>
    </div>

    <!-- Modal de cámara para desktop/laptop (getUserMedia) -->
    <Teleport to="body">
        <div v-if="showCameraModal"
            class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4"
            @click.self="cerrarCamara">
            <div class="bg-white rounded-2xl overflow-hidden w-full max-w-md shadow-2xl">

                <!-- Header -->
                <div class="bg-yamaha-blue px-5 py-3 flex items-center justify-between">
                    <h3 class="text-white font-semibold font-estricta text-sm">Tomar fotografía</h3>
                    <button @click="cerrarCamara"
                        class="text-blue-200 hover:text-white p-1 rounded transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Stream de video -->
                <div class="bg-black relative">
                    <video ref="videoEl" autoplay playsinline muted
                        class="w-full max-h-72 object-contain block"/>
                    <!-- Canvas oculto para capturar el frame -->
                    <canvas ref="canvasEl" class="hidden"/>
                </div>

                <!-- Error si la cámara no está disponible -->
                <p v-if="errorCamara" class="px-5 py-3 text-sm text-red-600 font-estricta bg-red-50 text-center">
                    {{ errorCamara }}
                </p>

                <!-- Acciones -->
                <div class="flex gap-3 px-5 py-4 justify-center border-t border-gray-100">
                    <button
                        type="button"
                        @click="capturarFoto"
                        :disabled="!!errorCamara"
                        class="flex items-center gap-2 px-6 py-2.5 bg-yamaha-red hover:bg-yamaha-red-dark disabled:opacity-50
                               text-white font-semibold rounded-lg font-estricta transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                        </svg>
                        Capturar
                    </button>
                    <button
                        type="button"
                        @click="cerrarCamara"
                        class="px-6 py-2.5 border border-gray-300 text-gray-600 font-semibold rounded-lg font-estricta hover:border-gray-400 transition-colors"
                    >
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { computed, ref, reactive, nextTick, onMounted, onUnmounted } from 'vue';
import { useForm, usePage, Link, Head } from '@inertiajs/vue3';

defineProps({
    modelos: Array,
});

const page        = usePage();
const fileInput   = ref(null);
const cameraInput = ref(null);
const previewUrl  = ref(null);

// Detección de dispositivo móvil/tablet iOS-Android (en esos ya funciona capture="environment")
const isMobile = /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

// Estado del modal de cámara para desktop
const showCameraModal = ref(false);
const errorCamara     = ref('');
const videoEl         = ref(null);
const canvasEl        = ref(null);
let   mediaStream     = null;

const abrirCamara = async () => {
    // Móvil/tablet: delega al input nativo con capture="environment"
    if (isMobile) {
        cameraInput.value.click();
        return;
    }
    // Desktop: solicita acceso a la webcam vía API del navegador
    errorCamara.value   = '';
    showCameraModal.value = true;
    await nextTick();
    try {
        mediaStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
        if (videoEl.value) videoEl.value.srcObject = mediaStream;
    } catch {
        errorCamara.value = 'No se pudo acceder a la cámara. Verifica los permisos del navegador.';
    }
};

const cerrarCamara = () => {
    if (mediaStream) {
        mediaStream.getTracks().forEach(t => t.stop());
        mediaStream = null;
    }
    showCameraModal.value = false;
    errorCamara.value     = '';
};

const capturarFoto = () => {
    const video  = videoEl.value;
    const canvas = canvasEl.value;
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    canvas.toBlob(blob => {
        const file = new File([blob], `vin-webcam-${Date.now()}.jpg`, { type: 'image/jpeg' });
        setFile(file);
        cerrarCamara();
    }, 'image/jpeg', 0.92);
};

const showSuccess = computed(() => !!page.props.flash?.success);

// ── Year Picker ────────────────────────────────────────────────────────────
const anioActual       = new Date().getFullYear();
const aniosDisponibles = computed(() => {
    const years = [];
    for (let y = anioActual; y >= 2000; y--) years.push(y);
    return years;
});
const yearPickerRef  = ref(null);
const yearPickerOpen = ref(false);

const seleccionarAnio = (anio) => {
    form.anio_modelo = String(anio);
    yearPickerOpen.value = false;
    delete localErrors.anio_modelo;
};

const onClickOutsideYear = (e) => {
    if (yearPickerRef.value && !yearPickerRef.value.contains(e.target))
        yearPickerOpen.value = false;
};
onMounted(()  => document.addEventListener('mousedown', onClickOutsideYear));
onUnmounted(() => document.removeEventListener('mousedown', onClickOutsideYear));

const form = useForm({
    nombres_apellidos: '',
    celular:           '',
    correo:            '',
    modelo_moto_id:    '',
    modelo_moto_otro:  '',
    anio_modelo:       '',
    vin_descripcion:   '',
    vin_imagen:        null,
});

const esOtroModelo = computed(() => form.modelo_moto_id === 'otros');

const aceptaTerminos = ref(false);
const localErrors    = reactive({});

function onLogoError(e) {
    e.target.style.display = 'none'
}

const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
const MAX_SIZE_MB   = 5;

const setFile = (file) => {
    if (!file) return;
    form.vin_imagen  = file;
    previewUrl.value = URL.createObjectURL(file);
    delete localErrors.vin_imagen;
};

const onFileChange = (e) => setFile(e.target.files[0]);
const onDrop       = (e) => setFile(e.dataTransfer.files[0]);

const validate = () => {
    Object.keys(localErrors).forEach(k => delete localErrors[k]);

    if (!form.nombres_apellidos.trim())
        localErrors.nombres_apellidos = 'El nombre es obligatorio.';
    else if (form.nombres_apellidos.trim().length < 3)
        localErrors.nombres_apellidos = 'Mínimo 3 caracteres.';

    if (!form.celular.trim())
        localErrors.celular = 'El celular es obligatorio.';
    else if (!/^[0-9+\s\-\(\)]{6,20}$/.test(form.celular.trim()))
        localErrors.celular = 'Formato de celular inválido.';

    if (!form.correo.trim())
        localErrors.correo = 'El correo es obligatorio.';
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.correo.trim()))
        localErrors.correo = 'Ingresa un correo electrónico válido.';

    if (!form.modelo_moto_id)
        localErrors.modelo_moto_id = 'Selecciona un modelo de moto.';
    else if (form.modelo_moto_id === 'otros' && !form.modelo_moto_otro.trim())
        localErrors.modelo_moto_otro = 'Escribe el modelo de tu moto.';

    if (!form.anio_modelo)
        localErrors.anio_modelo = 'El año de modelo es obligatorio.';

    if (!form.vin_descripcion.trim())
        localErrors.vin_descripcion = 'El código VIN es obligatorio.';
    else if (form.vin_descripcion.trim().length < 5)
        localErrors.vin_descripcion = 'El código VIN parece demasiado corto.';

    if (!form.vin_imagen)
        localErrors.vin_imagen = 'La foto del VIN es obligatoria.';
    else if (!ALLOWED_TYPES.includes(form.vin_imagen.type))
        localErrors.vin_imagen = 'Solo se permiten imágenes JPG, PNG, GIF o WEBP.';
    else if (form.vin_imagen.size > MAX_SIZE_MB * 1024 * 1024)
        localErrors.vin_imagen = `La imagen no debe superar ${MAX_SIZE_MB} MB.`;

    if (!aceptaTerminos.value)
        localErrors.terminos = 'Debes aceptar los Términos y condiciones para continuar.';

    return Object.keys(localErrors).length === 0;
};

const submit = () => {
    if (!validate()) return;
    form.post(route('vin.store'), {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<style scoped>
input, select {
    @apply py-2.5 px-3.5;
}
</style>
