<link rel="stylesheet" href="{{ asset('ckeditor5/ckeditor5.css') }}">
<script src="{{ asset('ckeditor5/ckeditor5.umd.js') }}"></script>
<script src="{{ asset('ckeditor5/translations/vi.umd.js') }}"></script>
<style>
    .ck-editor__editable_inline {
        min-height: 400px;
    }
</style>
<script src="{{ asset('ckeditor5/init.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        initCKEditor("{{ $editorId ?? 'content-editor' }}", "{!! $uploadUrl !!}");
    });
</script>
