<script>
$(document).ready(function () {

    // ── Summernote ───────────────────────────────────────────────
    $('.summernote').summernote({
        height: 400,
        lang: 'id-ID',
        placeholder: 'Tulis isi konten di sini…',
        toolbar: [
            ['style',    ['style']],
            ['font',     ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color',    ['color']],
            ['para',     ['ul', 'ol', 'paragraph']],
            ['height',   ['height']],
            ['table',    ['table']],
            ['insert',   ['link', 'picture', 'video', 'hr']],
            ['view',     ['fullscreen', 'codeview']],
        ],
        callbacks: {
            onImageUpload: function (files) {
                // Bisa pilih/seret beberapa gambar sekaligus
                Array.prototype.forEach.call(files, uploadKontenImage);
            }
        }
    });

    // Upload gambar ke server, lalu sisipkan ke editor.
    // Penghapusan gambar dari storage TIDAK dilakukan dari browser;
    // controller yang membandingkan isi lama vs baru saat disimpan / dihapus.
    function uploadKontenImage(file) {
        var data = new FormData();
        data.append('image', file);
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "{{ route('blog.upload.image') }}",
            method: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.url) $('.summernote').summernote('insertImage', res.url);
            },
            error: function (xhr) {
                var pesan = 'Upload gambar gagal.';
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    pesan = Object.values(xhr.responseJSON.errors)[0][0];
                }
                alert(pesan);
                console.error('Upload gagal:', xhr.responseText);
            }
        });
    }

    // ── Preview gambar sampul ────────────────────────────────────
    var gambarInput       = document.getElementById('gambar-input');
    var gambarPreview     = document.getElementById('gambar-preview');
    var gambarPlaceholder = document.getElementById('gambar-placeholder');
    var dropZone          = document.getElementById('gambar-drop-zone');
    var hapusSampul       = document.getElementById('hapus_sampul');

    function showPreview(file) {
        if (!file || !file.type.match('image.*')) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            if (gambarPreview)     { gambarPreview.src = e.target.result; gambarPreview.style.display = 'block'; }
            if (gambarPlaceholder) { gambarPlaceholder.style.display = 'none'; }
            if (hapusSampul)       { hapusSampul.checked = false; }
        };
        reader.readAsDataURL(file);
    }

    if (gambarInput) {
        gambarInput.addEventListener('change', function () {
            if (this.files && this.files[0]) showPreview(this.files[0]);
        });
    }

    if (dropZone) {
        dropZone.addEventListener('dragover', function (e) {
            e.preventDefault(); dropZone.classList.add('drag-over');
        });
        dropZone.addEventListener('dragleave', function () {
            dropZone.classList.remove('drag-over');
        });
        dropZone.addEventListener('drop', function (e) {
            e.preventDefault(); dropZone.classList.remove('drag-over');
            var file = e.dataTransfer.files[0];
            if (file && gambarInput) {
                var dt = new DataTransfer();
                dt.items.add(file);
                gambarInput.files = dt.files;
                showPreview(file);
            }
        });
    }

    // ── Char counter ─────────────────────────────────────────────
    function initCounter(inputId, counterId, max) {
        var el      = document.getElementById(inputId);
        var counter = document.getElementById(counterId);
        if (!el || !counter) return;
        var update = function () {
            var len = el.value.length;
            counter.textContent = len + ' / ' + max;
            counter.className   = 'char-counter' +
                (len >= max ? ' danger' : len >= max * 0.85 ? ' warning' : '');
        };
        el.addEventListener('input', update);
        update();
    }
    initCounter('judul',     'judul-counter',     255);
    initCounter('ringkasan', 'ringkasan-counter', 255);

});
</script>
