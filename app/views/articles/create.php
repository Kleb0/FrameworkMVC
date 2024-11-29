<?php require APPROOT . '/views/bases/header.php'; ?>

<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<style>
    /* Styles pour les tailles de texte */
    .ql-size-small {
        font-size: 12px;
    }

    .ql-size-medium {
        font-size: 16px;
    }

    .ql-size-large {
        font-size: 20px;
    }

    .ql-size-huge {
        font-size: 24px;
    }

    /* Style pour le bouton de suppression */
    .remove-paragraph-btn {
        display: block;
        margin-top: 10px;
    }
</style>

<div class="container mt-5">
    <h1 class="mb-4"><?= $data['title'] ?></h1>

    <form action="<?= URLROOT ?>/articles/save" method="POST" id="articleForm">
        <!-- Titre -->
        <div class="form-group mb-4">
            <label for="title">Titre de l'article</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="Entrez le titre de l'article" required>
        </div>

        <!-- Conteneur pour le contenu global -->
        <div class="form-group mb-4">
            <label for="hidden-content-editor">Contenu de l'article</label>
            <div id="content-editor" class="editor" style="height: 50px; border: 1px solid #ced4da;"></div>
            <input type="hidden" name="content" id="hidden-content-editor">
        </div>

        <!-- Conteneur pour les paragraphes -->
        <div id="paragraph-container">
            <!-- Paragraphe initial -->
            <div class="form-group mb-4 paragraph-block" data-id="1">
                <label for="hidden-title-editor-1">Titre du paragraphe</label>
                <div id="title-editor-1" class="editor-title mb-2" style="height: 50px; border: 1px solid #ced4da;"></div>
                <input type="hidden" name="paragraph_titles[]" id="hidden-title-editor-1">
                <label for="hidden-editor-1">Contenu du paragraphe</label>
                <div id="editor-1" class="editor" style="height: 200px; border: 1px solid #ced4da;"></div>
                <input type="hidden" name="paragraphs[]" id="hidden-editor-1">
                <!-- Bouton pour supprimer le paragraphe -->
                <button type="button" class="btn btn-danger remove-paragraph-btn" onclick="removeParagraph(1)">Supprimer ce paragraphe</button>
            </div>
        </div>

        <div class="button-group mb-4">
            <!-- Bouton pour ajouter un paragraphe -->
            <button type="button" id="add-paragraph" class="btn btn-secondary">Ajouter un paragraphe</button>

            <button type="submit" class="btn btn-primary">Publier l'article</button>
        </div>
    </form>
</div>

<script>
    const editors = [];
    const titleEditors = [];
    let editorCount = 1;

    // Options de la barre d'outils avec un handler personnalisé pour les images
    const toolbarOptions = [
        ['bold', 'italic', 'underline', { color: [] }, { background: [] }],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['link', 'image'], // Ajouter image ici
    ];

    // Fonction de gestion des images pour Quill
    function imageHandler() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = async () => {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const base64 = e.target.result;

                    // Insérer l'image dans l'éditeur sous forme de base64
                    const range = this.quill.getSelection();
                    this.quill.insertEmbed(range.index, 'image', base64);
                };
                reader.readAsDataURL(file);

                // Optionnel : Téléversez l'image sur le serveur pour obtenir une URL
                // const url = await uploadImageToServer(file);
                // const range = this.quill.getSelection();
                // this.quill.insertEmbed(range.index, 'image', url);
            }
        };
    }

    // Fonction pour téléverser l'image sur le serveur et obtenir une URL (optionnel)
    async function uploadImageToServer(file) {
        const formData = new FormData();
        formData.append('image', file);

        const response = await fetch('/upload-image-endpoint', {
            method: 'POST',
            body: formData,
        });

        const data = await response.json();
        return data.url; // Assurez-vous que le backend retourne l'URL de l'image
    }

    // Initialisation de Quill pour le titre du premier paragraphe
    const quillTitle1 = new Quill('#title-editor-1', {
        theme: 'snow',
        modules: {
            toolbar: {
                container: toolbarOptions,
                handlers: {
                    image: imageHandler, // Handler personnalisé pour les images
                },
            },
        },
    });
    titleEditors.push({ quill: quillTitle1, hiddenInput: document.getElementById('hidden-title-editor-1') });

    quillTitle1.on('text-change', function () {
        document.getElementById('hidden-title-editor-1').value = quillTitle1.root.innerHTML;
    });

    // Initialisation de Quill pour le premier paragraphe
    const quill1 = new Quill('#editor-1', {
        theme: 'snow',
        modules: {
            toolbar: {
                container: toolbarOptions,
                handlers: {
                    image: imageHandler, // Handler personnalisé pour les images
                },
            },
        },
    });
    editors.push({ quill: quill1, hiddenInput: document.getElementById('hidden-editor-1') });

    quill1.on('text-change', function () {
        document.getElementById('hidden-editor-1').value = quill1.root.innerHTML;
    });

    // Ajouter dynamiquement un nouveau bloc de paragraphe
    document.getElementById('add-paragraph').addEventListener('click', function () {
        const container = document.getElementById('paragraph-container');
        const newId = editors.length + 2;

        const newBlock = document.createElement('div');
        newBlock.classList.add('form-group', 'mb-4', 'paragraph-block');
        newBlock.setAttribute('data-id', newId);

        // Éditeur pour le titre
        const titleLabel = document.createElement('label');
        titleLabel.setAttribute('for', `hidden-title-editor-${newId}`);
        titleLabel.textContent = "Titre du paragraphe";

        const titleEditorDiv = document.createElement('div');
        titleEditorDiv.setAttribute('id', `title-editor-${newId}`);
        titleEditorDiv.classList.add('editor-title', 'mb-2');
        titleEditorDiv.style.height = '50px';
        titleEditorDiv.style.border = '1px solid #ced4da';

        const hiddenTitleInput = document.createElement('input');
        hiddenTitleInput.type = 'hidden';
        hiddenTitleInput.name = 'paragraph_titles[]';
        hiddenTitleInput.id = `hidden-title-editor-${newId}`;

        // Éditeur pour le contenu
        const editorLabel = document.createElement('label');
        editorLabel.setAttribute('for', `hidden-editor-${newId}`);
        editorLabel.textContent = "Contenu du paragraphe";

        const editorDiv = document.createElement('div');
        editorDiv.setAttribute('id', `editor-${newId}`);
        editorDiv.classList.add('editor');
        editorDiv.style.height = '200px';
        editorDiv.style.border = '1px solid #ced4da';

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'paragraphs[]';
        hiddenInput.id = `hidden-editor-${newId}`;

        // Bouton pour supprimer le paragraphe
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.textContent = "Supprimer ce paragraphe";
        removeButton.classList.add('btn', 'btn-danger', 'remove-paragraph-btn');
        removeButton.style.marginTop = "10px";
        removeButton.onclick = function () {
            removeParagraph(newId);
        };

        // Ajout au bloc
        newBlock.appendChild(titleLabel);
        newBlock.appendChild(titleEditorDiv);
        newBlock.appendChild(hiddenTitleInput);
        newBlock.appendChild(editorLabel);
        newBlock.appendChild(editorDiv);
        newBlock.appendChild(hiddenInput);
        newBlock.appendChild(removeButton);
        container.appendChild(newBlock);

        // Initialisation des éditeurs Quill
        const quillTitle = new Quill(`#title-editor-${newId}`, {
            theme: 'snow',
            modules: {
                toolbar: {
                    container: toolbarOptions,
                    handlers: {
                        image: imageHandler, // Handler personnalisé pour les images
                    },
                },
            },
        });
        titleEditors.push({ quill: quillTitle, hiddenInput: hiddenTitleInput });

        quillTitle.on('text-change', function () {
            hiddenTitleInput.value = quillTitle.root.innerHTML;
        });

        const quill = new Quill(`#editor-${newId}`, {
            theme: 'snow',
            modules: {
                toolbar: {
                    container: toolbarOptions,
                    handlers: {
                        image: imageHandler, // Handler personnalisé pour les images
                    },
                },
            },
        });
        editors.push({ quill: quill, hiddenInput: hiddenInput });

        quill.on('text-change', function () {
            hiddenInput.value = quill.root.innerHTML;
        });
    });

    // Fonction pour supprimer un paragraphe
    function removeParagraph(id) {
        const block = document.querySelector(`.paragraph-block[data-id="${id}"]`);
        if (block) {
            block.remove();
        }
    }

    // Sauvegarder tous les éditeurs avant soumission
    document.getElementById('articleForm').addEventListener('submit', function () {
        titleEditors.forEach(editor => {
            editor.hiddenInput.value = editor.quill.root.innerHTML;
        });
        editors.forEach(editor => {
            editor.hiddenInput.value = editor.quill.root.innerHTML;
        });
    });

    // Initialisation de Quill pour le contenu global
    const quillContent = new Quill('#content-editor', {
        theme: 'snow',
        modules: {
            toolbar: {
                container: toolbarOptions,
                handlers: {
                    image: imageHandler, // Handler personnalisé pour les images
                },
            },
        },
    });
    document.getElementById('hidden-content-editor').value = quillContent.root.innerHTML;

    quillContent.on('text-change', function () {
        document.getElementById('hidden-content-editor').value = quillContent.root.innerHTML;
    });

</script>

<?php require APPROOT . '/views/bases/footer.php'; ?>
