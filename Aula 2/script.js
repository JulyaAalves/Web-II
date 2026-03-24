document.addEventListener("DOMContentLoaded", function(){
    var form = document.getElementById("f")

    form.addEventListener("submit",function(e){
        e.preventDefault();
        var txt =document.getElementById("txt");
        var msg =document.getElementById("msg");

        msg.innerHTML=txt.value;

    });
});
    