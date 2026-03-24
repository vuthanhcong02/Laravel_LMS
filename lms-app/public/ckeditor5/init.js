function initCKEditor(editorId, uploadUrl) {
    const {
        ClassicEditor, Essentials, Bold, Italic, Underline, Strikethrough, Subscript, Superscript, Code,
        Link, Paragraph, Heading, List, TodoList, Image, ImageToolbar, ImageCaption, ImageStyle, ImageUpload,
        SimpleUploadAdapter, Font, Alignment, BlockQuote, Indent, IndentBlock, MediaEmbed, Table, TableToolbar,
        HorizontalLine, FindAndReplace, SourceEditing, RemoveFormat, PageBreak, Highlight
    } = CKEDITOR;

    ClassicEditor.create(document.querySelector('#' + editorId), {
        licenseKey: 'GPL',
        plugins: [
            Essentials, Bold, Italic, Underline, Strikethrough, Subscript, Superscript, Code,
            Link, Paragraph, Heading, List, TodoList, Image, ImageToolbar, ImageCaption, ImageStyle, ImageUpload,
            SimpleUploadAdapter, Font, Alignment, BlockQuote, Indent, IndentBlock, MediaEmbed, Table, TableToolbar,
            HorizontalLine, FindAndReplace, SourceEditing, RemoveFormat, PageBreak, Highlight
        ],
        toolbar: {
            items: [
                'sourceEditing', '|',
                'undo', 'redo', '|',
                'findAndReplace', '|',
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript', 'code', 'removeFormat', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                'alignment', '|',
                'bulletedList', 'numberedList', 'todoList', 'indent', 'outdent', '|',
                'link', 'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', 'horizontalLine', 'pageBreak'
            ],
            shouldNotGroupWhenFull: true
        },
        language: 'vi',
        image: {
            toolbar: [
                'imageTextAlternative', 'toggleImageCaption', '|',
                'imageStyle:inline', 'imageStyle:block', 'imageStyle:side'
            ]
        },
        table: {
            contentToolbar: [
                'tableColumn', 'tableRow', 'mergeTableCells'
            ]
        },
        simpleUpload: {
            uploadUrl: uploadUrl
        }
    }).then(editor => {
        window.editor = editor;
    }).catch(error => {
        console.error(error);
    });
}
