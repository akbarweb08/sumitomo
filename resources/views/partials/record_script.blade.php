<script src="{{ asset('js/sweetalert2@11.js') }}"></script>
<script>
    function recordData(lotNumber) {
        if(!lotNumber) {
            Swal.fire('Error!', 'Lot Number tidak ditemukan.', 'error');
            return;
        }
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin merekap data pallet hari ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Record!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('sketch.record') }}",
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content') || "{{ csrf_token() }}",
                        LotNumber: lotNumber
                    },
                    success: function(response) {
                        if(response.status == 'success') {
                            Swal.fire(
                                'Berhasil!',
                                response.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Gagal!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat merekap data.',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
