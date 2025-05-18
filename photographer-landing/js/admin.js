document.addEventListener('DOMContentLoaded', function() {
    // Вход в админку
    const loginForm = document.getElementById('adminLoginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            // Проверка логина и пароля
            if (username === 'admin' && password === 'admin123') {
                localStorage.setItem('adminLoggedIn', 'true');
                window.location.href = 'dashboard.html';
            } else {
                alert('Неверный логин или пароль');
            }
        });
    }

    // Проверка авторизации
    const protectedPages = ['dashboard.html', 'gallery-manager.html', 'bookings-view.html'];
    if (protectedPages.some(page => window.location.pathname.includes(page))) {
        if (localStorage.getItem('adminLoggedIn') !== 'true') {
            window.location.href = 'index.html';
        }
    }

    // Выход из админки
    const logoutBtn = document.getElementById('logout');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Вы уверены, что хотите выйти?')) {
                localStorage.removeItem('adminLoggedIn');
                window.location.href = 'index.html';
            }
        });
    }

    // Окно загрузки фото
    const addPhotoBtn = document.getElementById('addPhotoBtn');
    const uploadModal = document.getElementById('uploadModal');
    const closeModal = document.querySelectorAll('.close-modal');
    
    if (addPhotoBtn && uploadModal) {
        addPhotoBtn.addEventListener('click', function() {
            uploadModal.style.display = 'flex';
        });
        
        closeModal.forEach(btn => {
            btn.addEventListener('click', function() {
                uploadModal.style.display = 'none';
            });
        });
        
        window.addEventListener('click', function(e) {
            if (e.target === uploadModal) {
                uploadModal.style.display = 'none';
            }
        });
    }

    // Обработка загрузки фото
    const uploadForm = document.getElementById('uploadForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Здесь должна быть реальная загрузка на сервер
            alert('Фото успешно загружено (в реальном проекте здесь будет AJAX запрос)');
            this.reset();
            uploadModal.style.display = 'none';
        });
    }

    // Удаление фото
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const photoId = this.getAttribute('data-id');
            if (confirm('Вы уверены, что хотите удалить это фото?')) {
                // Здесь должен быть запрос на удаление
                this.closest('.photo-item').remove();
                alert('Фото удалено (в реальном проекте здесь будет AJAX запрос)');
            }
        });
    });

    // Изменение категории фото
    const categorySelects = document.querySelectorAll('.photo-category');
    categorySelects.forEach(select => {
        select.addEventListener('change', function() {
            const photoId = this.getAttribute('data-id');
            const newCategory = this.value;
            // Здесь должен быть запрос на обновление категории
            alert(`Категория фото ${photoId} изменена на ${newCategory} (в реальном проекте здесь будет AJAX запрос)`);
        });
    });

    // Фильтрация фото по категориям
    const categoryFilter = document.getElementById('categoryFilter');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            const filterValue = this.value;
            const photoItems = document.querySelectorAll('.photo-item');
            
            photoItems.forEach(item => {
                const itemCategory = item.querySelector('.photo-category').value;
                
                if (filterValue === 'all' || itemCategory === filterValue) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Фильтрация заявок
    const statusFilter = document.getElementById('statusFilter');
    const dateFilter = document.getElementById('dateFilter');
    
    if (statusFilter) {
        statusFilter.addEventListener('change', filterBookings);
    }
    
    if (dateFilter) {
        dateFilter.addEventListener('change', filterBookings);
    }
    
    function filterBookings() {
        const statusValue = statusFilter ? statusFilter.value : 'all';
        const dateValue = dateFilter ? dateFilter.value : '';
        
        const rows = document.querySelectorAll('.bookings-table tbody tr');
        
        rows.forEach(row => {
            const rowStatus = row.querySelector('td:nth-child(7) span').className.replace('status-', '');
            const rowDate = row.querySelector('td:nth-child(5)').textContent;
            
            const statusMatch = statusValue === 'all' || rowStatus === statusValue;
            const dateMatch = !dateValue || rowDate.includes(dateValue.split('-').reverse().join('.'));
            
            if (statusMatch && dateMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Модальное окно деталей заявки
    const bookingDetailsModal = document.getElementById('bookingDetailsModal');
    const viewButtons = document.querySelectorAll('.btn-view');
    
    if (bookingDetailsModal) {
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const bookingId = this.getAttribute('data-id');
                const row = this.closest('tr');
                
                // Заполняем модальное окно данными из строки таблицы
                document.getElementById('bookingId').textContent = bookingId;
                document.getElementById('detailName').textContent = row.cells[1].textContent;
                document.getElementById('detailPhone').textContent = row.cells[2].textContent;
                document.getElementById('detailEmail').textContent = row.cells[3].textContent;
                document.getElementById('detailDate').textContent = row.cells[4].textContent;
                document.getElementById('detailType').textContent = row.cells[5].textContent;
                document.getElementById('detailStatus').textContent = row.cells[6].textContent;
                document.getElementById('detailMessage').textContent = 'Дополнительная информация о заявке...';
                
                // Показываем модальное окно
                bookingDetailsModal.style.display = 'flex';
            });
        });
        
        const closeModal = bookingDetailsModal.querySelector('.close-modal');
        closeModal.addEventListener('click', function() {
            bookingDetailsModal.style.display = 'none';
        });
        
        window.addEventListener('click', function(e) {
            if (e.target === bookingDetailsModal) {
                bookingDetailsModal.style.display = 'none';
            }
        });
    }

    // Действия с заявками
    const confirmButtons = document.querySelectorAll('.btn-confirm');
    const completeButtons = document.querySelectorAll('.btn-complete');
    const cancelButtons = document.querySelectorAll('.btn-cancel');
    
    confirmButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const bookingId = this.getAttribute('data-id');
            
            if (confirm('Подтвердить эту заявку?')) {
                // Здесь должен быть запрос на подтверждение заявки
                const row = this.closest('tr');
                row.cells[6].innerHTML = '<span class="status-confirmed">Подтверждена</span>';
                alert('Заявка подтверждена (в реальном проекте здесь будет AJAX запрос)');
            }
        });
    });
    
    completeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const bookingId = this.getAttribute('data-id');
            
            if (confirm('Завершить эту заявку?')) {
                // Здесь должен быть запрос на завершение заявки
                const row = this.closest('tr');
                row.cells[6].innerHTML = '<span class="status-completed">Завершена</span>';
                alert('Заявка завершена (в реальном проекте здесь будет AJAX запрос)');
            }
        });
    });
    
    cancelButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const bookingId = this.getAttribute('data-id');
            
            if (confirm('Отменить эту заявку?')) {
                // Здесь должен быть запрос на отмену заявки
                const row = this.closest('tr');
                row.cells[6].innerHTML = '<span class="status-cancelled">Отменена</span>';
                alert('Заявка отменена (в реальном проекте здесь будет AJAX запрос)');
            }
        });
    });
});