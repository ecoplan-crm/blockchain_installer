import "./alpineComponents";
import persist from "@alpinejs/persist";
import Alpine from "alpinejs";
import Components from "./alpineComponents";

window.Alpine = Alpine;
window.Components = Components;
Alpine.plugin(persist);
Alpine.start();

window.nodejsPort = "3000";
window.appURL = 'localhost';

fetch('/env-variables')
.then(response => response.json())
.then(data => {
    window.nodejsPort = data.nodejsPort;
    window.appURL = data.appURL;
    console.log("Ausgelesene URL: " + window.appURL);
    console.log("Ausgelesener Port: " + window.nodejsPort);
})
.catch(error => {
    console.error('Fehler beim Abrufen der Environment-Variablen:', error);
});
const forms = document.querySelectorAll("form.script");

if (forms) {
    for (let form of forms) {
        const output = document.querySelector("#logOutput");

        form.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(form);
            const script = formData.get("script");

            let currentOutput = "";

            fetch(appURL + ":" + nodejsPort + "/execute-script", {
                method: "POST",
                body: JSON.stringify({
                    script,
                }),
                headers: {
                    "Content-Type": "application/json",
                },
            })
                .then((response) => {

                    const reader = response.body.getReader();

                    reader.read().then(function processText({ done, value }) {


                        if (done) {
                            if (output) {
                                output.insertAdjacentHTML(
                                    "beforeend",
                                    "<br><br>Skriptausführung beendet"
                                );
                                Livewire.emit('finished', output.innerHTML);
                            } else {
                                Livewire.emit('finished', null);
                            }

                            scrollToBottom();

                            return;
                        }

                        const text = new TextDecoder("utf-8").decode(value);
                        currentOutput += text

                            .replaceAll("[0m", "</font>")
                            .replaceAll("[34m", "<font color=blue>")
                            .replaceAll("[34;1m", "<font color=blue>")
                            .replaceAll("[0;34m", "<font color=blue>")
                            .replaceAll("[0;33m", "<font color=orange>")
                            .replaceAll("[1;33m",   "<font color=orange>")
                            .replaceAll("[0;32m", "<font color=green>")
                            .replaceAll("[0;31m", "<font color=red>")
                            .replaceAll("[31m",   "<font color=red>")
                            .replaceAll("[31;1m",   "<font color=red>")
                           ;

                            if (output) {
                                output.innerHTML = currentOutput; // Aktualisiere den gesamten Inhalt der Ausgabe
                                scrollToBottom();
                            }

                        return reader.read().then(processText);
                    });
                })
                .catch((error) => {
                    console.error("Fehler bei der Skriptausführung:", error);
                });
        });
    }
}

function scrollToBottom() {
    window.scrollTo({
        top: document.body.scrollHeight,
        behavior: "smooth",
    });
}

setInterval(function () {
    Livewire.emitTo("docker", "refreshComponent");
}, 2000);
