Searchinput = document.getElementById("searchInput");
$('#searchInput').on('input', function() {
    var keyWord = $(this).val(); 
    if (keyWord != "") {
        $.ajax({
            url: './articleSearch.php', 
            method: 'POST',
            data: { SearchInput: keyWord },
            success: function(response) {
                $('#resultarea').html(response); 
            }
        });
    } 
});
