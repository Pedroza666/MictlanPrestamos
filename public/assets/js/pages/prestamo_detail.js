let formEstado = document.querySelectorAll('.formEstado');
const btnCorreo = document.querySelector('#btnCorreo');
const btnWhatsApp = document.querySelector('#btnWhatsApp');
const btnWhatsappWeb = document.querySelector('#btnWhatsappWeb');
const mensaje_whatspp = document.querySelector('#mensaje-whatsapp');
const num_whatsapp = document.querySelector('#num-whatsapp');
const myModal = new bootstrap.Modal(document.getElementById('modalMensaje'));
const modalWhatsApp = new bootstrap.Modal(document.getElementById('modalWhatsApp'));

document.addEventListener('DOMContentLoaded', function(){
    for (let i= 0; i< formEstado.length; i++){
        formEstado[i].addEventListener('submit', function(e){
            e.preventDefault();
            cambiarEstado(this);
        })
    }
 
    //modal correo electronico
    btnCorreo.addEventListener('click', function(){
        myModal.show();
    })

    //modal whatsapp 
    btnWhatsApp.addEventListener('click', function(){
        modalWhatsApp.show();
    })

    btnWhatsappWeb.addEventListener('click', function(){
        if (mensaje_whatspp.value == '' || num_whatsapp.value == '') {
            Swal.fire({
                position: 'top-end',
                icon: 'warning',
                title: 'El mensaje y whatssappes requerido',
                showConfirmButton: false,
                timer: 1500
              })
        }else{
            window.open(`https://api.whatsapp.com/send?phone=${num_whatsapp.value}&text=${mensaje_whatspp.value}`, '_blank');
        }
       
    })
})



function cambiarEstado(form){
    Swal.fire({
        title: 'Estas a punto de Realizar un pago a la cueta del cliente',
        text: "Deseas continuar ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })

}

