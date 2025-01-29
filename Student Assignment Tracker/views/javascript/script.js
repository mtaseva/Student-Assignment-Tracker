const container = document.getElementById("container");
const registerbtn = document.getElementById("register");
const loginbtn = document.getElementById("login");

registerbtn.addEventListener("click", () => {
  container.classList.add("active");
});

loginbtn.addEventListener("click", () => {
  container.classList.remove("active");
});


document.addEventListener("DOMContentLoaded", function() {

    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get("status");
    const action = urlParams.get("action");

    if(status === "success") {

        if(action === "register") {

            Swal.fire({
                icon: "success",
                title: "Регистрацијата е успешна!",
                text: "Сега можеш да се најавиш.",
                confirmButtonText: "Најави се"
            }).then(() => {
              // dokolku registracijata e uspesna,
              // korisnikot go prenosocuvame na formata za najava
                document.getElementById("login").click();
                window.location.replace("authentication.html");
            });

        } else if(action === "login") {

            Swal.fire({
                icon: "success",
                title: "Најавувањето е успешно!",
                text: "Се пренасочуваш кон почетната страница...",
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
              // dokolku najavata e uspesna,
              // korisnikot go prenosocuvame na pocetnata strana
              window.location.href = "dashboard.html";
            });

        } else if(action === "logout") {

          Swal.fire({
            icon: "success",
            title: "Успешно се одјавивте!",
            text: "Ве очекуваме повторно.",
            confirmButtonText: "Затвори"
          }).then(() => {
            // posle uspesna odjava go prenosocuvame korisnikot kon formata za avtentikacija
            window.location.replace("authentication.html");
          });

        }

    } else if(status === "failure") {

        const message = action === "register"
            ? "Регистрацијата не успеа. Ве молиме обидете се повторно."
            : "Најавувањето не успеа. Ве молиме проверете ги вашите податоци.";
        Swal.fire({
            icon: "error",
            title: "Грешка!",
            text: message,
            confirmButtonText: "Обидете се повторно"
        });

    }
});

