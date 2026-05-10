

document.addEventListener('DOMContentLoaded', function () {

    
    const statusSelect = document.querySelector('select[name="status"]');
    const chapterGroup = document.getElementById('chapterGroup');

    function toggleChapterFields() {
        if (!statusSelect || !chapterGroup) return;
       
        chapterGroup.style.display = statusSelect.value === 'reading' ? 'grid' : 'none';
    }

    if (statusSelect) {
        statusSelect.addEventListener('change', toggleChapterFields);
        toggleChapterFields();
    }

   
    const typeSelect  = document.getElementById('bookTypeSelect');
    const linkGroup   = document.getElementById('linkGroup');
    const linkInput   = document.getElementById('bookLinkInput');

    function toggleLinkField() {
        if (!typeSelect || !linkGroup) return;
        const isOnline = typeSelect.value === 'online';
        linkGroup.style.display = isOnline ? 'block' : 'none';
        if (linkInput) {
            linkInput.required = isOnline;
        }
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', toggleLinkField);
        toggleLinkField(); 
    }

 
    const starPicker   = document.getElementById('starPicker');
    const ratingInput  = document.getElementById('ratingInput');

    if (starPicker && ratingInput) {
        const stars = starPicker.querySelectorAll('.star-option');
        let currentRating = parseInt(ratingInput.value) || 0;

       
        function paintStars(value) {
            stars.forEach(function (star) {
                const v = parseInt(star.dataset.val);
                star.classList.toggle('active', v <= value);
            });
        }

       
        paintStars(currentRating);

        stars.forEach(function (star) {
          
            star.addEventListener('mouseenter', function () {
                paintStars(parseInt(this.dataset.val));
                stars.forEach(s => s.classList.add('hover'));
            });

           
            star.addEventListener('mouseleave', function () {
                stars.forEach(s => s.classList.remove('hover'));
                paintStars(currentRating);
            });

            
            star.addEventListener('click', function () {
                const val = parseInt(this.dataset.val);
                if (currentRating === val) {
                    
                    currentRating = 0;
                    ratingInput.value = '';
                } else {
                    currentRating = val;
                    ratingInput.value = val;
                }
                paintStars(currentRating);
            });
        });
    }


    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function () { alert.remove(); }, 500);
        }, 4000);
    });

  
    const searchInput = document.querySelector('.search-input');
    const bookCards   = document.querySelectorAll('.book-card');

    
    if (searchInput && bookCards.length > 0) {
        searchInput.addEventListener('input', function () {
            
            const val = this.value.trim().toLowerCase();
            if (val === '') {
                bookCards.forEach(card => card.style.opacity = '1');
            }
          
        });
    }

});
