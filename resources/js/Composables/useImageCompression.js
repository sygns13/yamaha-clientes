/**
 * useImageCompression
 * ───────────────────────────────────────────────────────────────────────────
 * Comprime y redimensiona imágenes EN EL CLIENTE antes de subirlas al backend,
 * para acelerar la subida y reducir la carga del servidor bajo concurrencia.
 *
 * Reglas:
 *  - Redimensiona a un máximo de MAX_SIDE px en el lado más largo, manteniendo
 *    la relación de aspecto. NO agranda imágenes ya más pequeñas.
 *  - Exporta como JPEG con calidad JPEG_QUALITY (objetivo ~200-400 KB).
 *  - Imágenes HEIC/HEIF de iPhone: el canvas no las decodifica de forma fiable,
 *    así que primero se convierten a JPEG con heic2any.
 *
 * Garantía de robustez: si la compresión falla por CUALQUIER motivo, se devuelve
 * el archivo ORIGINAL. Nunca se debe impedir guardar la venta por un error de
 * compresión.
 */

// Lado más largo máximo (px) tras el redimensionado.
const MAX_SIDE = 1600;

// Calidad de exportación JPEG.
const JPEG_QUALITY = 0.85;

/** ¿El archivo es realmente una imagen (incluyendo HEIC/HEIF)? */
function esImagen(file) {
    if (!file) return false;
    if (file.type && file.type.startsWith('image/')) return true;
    // iOS a veces entrega HEIC con type vacío: detectamos por extensión.
    return /\.(heic|heif)$/i.test(file.name || '');
}

/** ¿Es HEIC/HEIF? (por MIME o por extensión, porque iOS a veces no setea type) */
function esHeic(file) {
    const tipo = (file.type || '').toLowerCase();
    if (tipo === 'image/heic' || tipo === 'image/heif') return true;
    return /\.(heic|heif)$/i.test(file.name || '');
}

/** Reemplaza la extensión del nombre por .jpg */
function nombreJpeg(name) {
    const base = (name || 'imagen').replace(/\.[^.]+$/, '');
    return `${base}.jpg`;
}

/** Carga un Blob/File en un HTMLImageElement ya decodificado. */
function cargarImagen(blob) {
    return new Promise((resolve, reject) => {
        const url = URL.createObjectURL(blob);
        const img = new Image();
        img.onload = () => {
            URL.revokeObjectURL(url);
            resolve(img);
        };
        img.onerror = () => {
            URL.revokeObjectURL(url);
            reject(new Error('No se pudo decodificar la imagen.'));
        };
        img.src = url;
    });
}

/** Convierte HEIC/HEIF a un Blob JPEG usando heic2any (import dinámico). */
async function heicAJpeg(file) {
    // Import dinámico: heic2any solo se descarga cuando realmente llega un HEIC.
    const { default: heic2any } = await import('heic2any');
    const resultado = await heic2any({ blob: file, toType: 'image/jpeg', quality: JPEG_QUALITY });
    // heic2any puede devolver un Blob o un array de Blobs (imágenes múltiples).
    return Array.isArray(resultado) ? resultado[0] : resultado;
}

/**
 * Comprime la imagen. Devuelve SIEMPRE un File:
 *  - Un nuevo File JPEG optimizado si todo salió bien.
 *  - El File original si no es imagen o si algo falla.
 *
 * @param {File} file
 * @returns {Promise<File>}
 */
export async function compressImage(file) {
    // Validar que sea realmente una imagen antes de procesar.
    if (!esImagen(file)) return file;

    try {
        // 1) HEIC/HEIF → JPEG (el canvas no los decodifica de forma fiable).
        const fuente = esHeic(file) ? await heicAJpeg(file) : file;

        // 2) Decodificar a un elemento <img>.
        const img = await cargarImagen(fuente);

        const { width, height } = img;
        if (!width || !height) return file; // dimensiones inválidas → original

        // 3) Calcular escala: máx. MAX_SIDE en el lado más largo, sin agrandar.
        const escala = Math.min(1, MAX_SIDE / Math.max(width, height));
        const destW  = Math.round(width * escala);
        const destH  = Math.round(height * escala);

        // 4) Dibujar en canvas.
        const canvas = document.createElement('canvas');
        canvas.width  = destW;
        canvas.height = destH;
        const ctx = canvas.getContext('2d');
        if (!ctx) return file;
        ctx.drawImage(img, 0, 0, destW, destH);

        // 5) Exportar como JPEG.
        const blob = await new Promise((resolve) =>
            canvas.toBlob(resolve, 'image/jpeg', JPEG_QUALITY)
        );
        if (!blob) return file; // toBlob falló → original

        // 6) Envolver en un File con nombre .jpg, conservando metadatos básicos.
        return new File([blob], nombreJpeg(file.name), {
            type: 'image/jpeg',
            lastModified: Date.now(),
        });
    } catch (e) {
        // Cualquier error → subir la imagen ORIGINAL. Nunca bloquear el registro.
        console.warn('[useImageCompression] Compresión fallida, se usa el original:', e);
        return file;
    }
}

export function useImageCompression() {
    return { compressImage };
}
