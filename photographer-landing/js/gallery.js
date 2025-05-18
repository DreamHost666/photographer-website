document.addEventListener('DOMContentLoaded', function() {
    // Элементы интерфейса
    const galleryContainer = document.getElementById('galleryContainer');
    const uploadForm = document.getElementById('uploadForm');
    const uploadModal = document.getElementById('uploadModal');
    const addPhotoBtn = document.getElementById('addPhotoBtn');
    const closeModal = document.querySelector('.close-modal');
    const categoryFilter = document.getElementById('categoryFilter');

    // Загрузка галереи при открытии
    if (galleryContainer) {
        loadGallery();
    }

    // Открытие модального окна
    if (addPhotoBtn) {
        addPhotoBtn.addEventListener('click', () => {
            uploadModal.style.display = 'flex';
        });
    }

    // Закрытие модального окна
    if (closeModal) {
        closeModal.addEventListener('click', () => {
            uploadModal.style.display = 'none';
        });
    }

    // Закрытие по клику вне окна
    window.addEventListener('click', (e) => {
        if (e.target === uploadModal) {
            uploadModal.style.display = 'none';
        }
    });

    // Обработка отправки формы
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Валидация размера файла
            const fileInput = document.getElementById('photoFile');
            if (fileInput.files[0] && fileInput.files[0].size > 5 * 1024 * 1024) {
                alert('Файл слишком большой (макс. 5MB)');
                return;
            }

            // Здесь должна быть реальная загрузка на сервер
            alert('Фото успешно загружено (в реальном проекте здесь будет AJAX запрос)');
            this.reset();
            uploadModal.style.display = 'none';
        });
    }

    // Фильтрация по категориям
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterGallery);
    }

    // Функция загрузки галереи
    function loadGallery() {
        // В реальном проекте здесь будет AJAX запрос к серверу
        console.log('Загрузка галереи...');
    }

    // Функция фильтрации галереи
    function filterGallery() {
        const category = categoryFilter.value;
        const items = document.querySelectorAll('.photo-item');
        
        items.forEach(item => {
            if (category === 'all' || item.dataset.category === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }
});