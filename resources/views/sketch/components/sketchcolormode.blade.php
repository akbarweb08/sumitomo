<script>
    function toggleModal(event, type = 'input') {
        splitted = $(event).attr("data-pallets").split(';');
        console.log(splitted[0]);
        var id = splitted[0];
        var color = "{{ request('color') }}";
        var mode = "{{ request('mode') }}";

        let data = {
            color: color,
            id: id,
            mode: mode,
            _token: '{{ csrf_token() }}'
        }
        
        $.ajax({
            type: "POST",
            url: '{{ route("sketch.colorModeProcess") ?? "#" }}',
            data: data,
            success: function(response) {
                console.log(response)
                if (response.status == 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    })
                    return;
                } else {
                    location.reload()
                }
            }
        });
    }

    function toggleColor(event) {
        splitted = $(event).attr("data-colors").split(';');
        console.log(splitted[0]);
        window.location.href = `{{ route('sketch.show', ['lot' => request()->segment(2)]) }}?color=${splitted[0]}&mode={{ request('mode') }}`;
    }

    function toggleColorInput() {
        window.location.href = `{{ route('sketch.show', ['lot' => request()->segment(2)]) }}?color=1&mode={{ request('mode') }}`;
    }
</script>
