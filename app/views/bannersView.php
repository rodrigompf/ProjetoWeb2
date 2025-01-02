<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banners</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Style for modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }
        .modal img {
            max-width: 90%;
            max-height: 90%;
        }
        .modal.active {
            display: flex;
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
    <a href="javascript:history.back()"
            class="absolute top-8 left-8 px-12 py-4 bg-gray-500 text-white font-semibold rounded shadow hover:bg-gray-600 transition">
            Voltar
        </a>
        <h1 class="text-2xl font-bold mb-4">Banners</h1>

        <?php if (!empty($error)): ?>
            <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p class="text-green-500"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <ul class="space-y-2">
            <?php foreach ($banners as $banner): ?>
                <li class="p-2 bg-gray-200 rounded flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <!-- Clickable image that opens in the modal -->
                        <a href="#" class="open-modal" data-image="<?= htmlspecialchars($banner['image_url']) ?>">
                            <img src="<?= htmlspecialchars($banner['image_url']) ?>" alt="Banner" class="w-16 h-16 object-cover rounded">
                        </a>
                        
                        <div>
                            <p><strong><?= htmlspecialchars($banner['name']) ?></strong></p>
                        </div>
                    </div>

                    <div class="flex space-x-2">
                        <a href="/banners/edit/<?= $banner['id'] ?>" class="text-blue-500 hover:underline">Editar</a>
                        <a href="/banners/delete/<?= $banner['id'] ?>" class="text-red-500 hover:underline">Eliminar</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <a href="/banners/create" class="mt-4 inline-block px-4 py-2 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition">
            Adicionar Banner
        </a>
    </div>

    <!-- Modal for viewing image in full-screen -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <img id="modal-image" src="" alt="Full-screen banner">
        </div>
    </div>

    <script>
        // Get the modal and image element
        const modal = document.getElementById('modal');
        const modalImage = document.getElementById('modal-image');
        
        // Get all elements with class 'open-modal' (clickable images)
        const openModalLinks = document.querySelectorAll('.open-modal');

        // Event listener for each clickable image
        openModalLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const imageUrl = this.getAttribute('data-image');
                modalImage.src = imageUrl; // Set the image in the modal
                modal.classList.add('active'); // Show the modal
            });
        });

        // Close the modal when clicked outside of the image
        modal.addEventListener('click', function() {
            modal.classList.remove('active');
        });
    </script>
</body>

</html>