import Swal from "sweetalert2";

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

export const confirmDialog = () => {
    return Swal.fire({
        title: 'Estas seguro?',
        text: 'Ya no podras revertir esto!',
        icon: 'warning',
        position: 'center',
        width: 400,
        height: 300,
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Si, Eliminar!'
    });
};

export const showToast = (title, icon) => {
    Toast.fire({
        icon: icon,
        title: title
    });
};
