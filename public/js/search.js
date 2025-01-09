


Searchinput = document.getElementById("searchInput");
// Search Part
$('#searchInput').on('input', function() {
    var searchText = $(this).val(); 

    if (searchText != "") {
        $.ajax({
            url: './articleSearch.php', 
            method: 'GET',
            data: { searchText: searchText },
            success: function(response) {
                $('#playerSearchResult').html(response); 
            }
        });
    } else {
        loadDashboard(); 
    }
});
  let keyword = $('#searchInput').val()
  console.log("hello");
  $.ajax({
    type: "POST",
    url: "http://localhost/You%20Code/Dev%20Blog/Dev%20Blog%20S3/App/Views/User/articlesearch.php",
    data: {
         search: keyword
     },
    success: function(data){
       $("#resultarea").html(html).show();
    }
  });