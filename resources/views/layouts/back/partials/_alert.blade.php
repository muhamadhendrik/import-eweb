@if (session('success') || session('error') || session('failed') || session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let type = '';
        let message = '';
        let confirmButtonClass = '';

        @if (session('success'))
            type = 'success';
            message = "{{ session('success') }}";
            confirmButtonClass = 'btn btn-primary waves-effect waves-light';
        @elseif (session('error'))
            type = 'error';
            message = "{{ session('error') }}";
            confirmButtonClass = 'btn btn-danger waves-effect waves-light';
        @elseif (session('failed'))
            type = 'error';
            message = "{{ session('failed') }}";
            confirmButtonClass = 'btn btn-danger waves-effect waves-light';
        @elseif (session('warning'))
            type = 'warning';
            message = "{{ session('warning') }}";
            confirmButtonClass = 'btn btn-warning waves-effect waves-light';
        @endif

        Swal.fire({
            title: type.charAt(0).toUpperCase() + type.slice(1),
            text: message,
            icon: type,
            customClass: {
                confirmButton: confirmButtonClass
            },
            buttonsStyling: false
        });
    });
</script>
@endif

@if(session('checkFailed'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: '<strong>Sorry <span style="color: #E74C3C;">John Doe</span></strong>',
                html: `
                    <p>Serial code <b>SY8JK5A</b> has been checked by someone else.</p>
                    <p>Please contact the seller where you shop!</p>
                    <hr>
                    <p style="font-size: 0.9rem; color: #555;">We would like to remind you that product quality and safety are very important for your pet's health.</p>
                    <p style="font-size: 0.9rem; color: #555;">If you have any concerns about the product you received, please do not hesitate to contact us at <b style="color: #3498DB;">0813-1566-1886</b></p>
                `,
                icon: 'warning',
                iconColor: '#E74C3C',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-primary waves-effect waves-light'
                },
                buttonsStyling: false
            });
        });
    </script>
@endif

@if(session('checkClaimed'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: `<strong>Maaf</strong>`,
                html: `
                    <p>{{ session('checkClaimed') }}</p>
                    <p>Silakan hubungi penjual tempat anda beli!</p>
                    <hr>
                    <p style="font-size: 0.9rem; color: #555;">Kami ingin mengingatkan anda bahwa kualitas dan keamanan produk sangat penting untuk kesehatan hewan peliharaan anda.</p>
                    <p style="font-size: 0.9rem; color: #555;">Jika ada kekhawatiran tentang produk yang anda terima, jangan ragu untuk menghubungi kami di: <a href="https://wa.me/+6281315661886"> <b style="color: #3498DB;">0813-1566-1886</b> </a></p>
                `,
                icon: 'warning',
                iconColor: '#E74C3C',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-primary waves-effect waves-light'
                },
                buttonsStyling: false
            });
        });
    </script>
@endif

@if(session('checkFailed'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                iconHtml: '<i class="fa fa-times-circle" style="color: #E74C3C; font-size: 80px;"></i>',
                title: 'Maaf kode serial anda tidak terdaftar!',
                html: `
                    <p style="margin-bottom: 10px;">Waspadalah terhadap pemalsuan produk kami</p>
                    <p>Silakan hubungi penjual tempat anda beli!</p>
                    <div style="font-size: 13px; color: #6c757d; margin-top: 20px;">
                        <p>Kami ingin mengingatkan anda bahwa kualitas dan keamanan produk sangat penting untuk kesehatan hewan peliharaan anda.</p>
                        <p>Jika ada kekhawatiran tentang produk yang anda terima, jangan ragu untuk menghubungi kami di: <span style="font-weight: bold;"><a href="https://wa.me/+6281315661886"> <b style="color: #3498DB;">0813-1566-1886</b> </a></span></p>
                    </div>
                `,
                customClass: {
                    popup: 'custom-popup',
                    confirmButton: 'custom-button',
                },
                showCloseButton: false,
                confirmButtonText: 'OK',
                buttonsStyling: false,
            });
        });
    </script>
@endif
