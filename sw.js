self.addEventListener("activate", event => {});

self.addEventListener("fetch", event => {});

self.addEventListener("push", event => {
  try {
    const data = event.data.json();
    if (data.typ === "vplan") {
      let titel = "Fehler: " + JSON.stringify(data);
      let verb = "fehler";
      switch (data.art) {
        case "e":
          titel = "Entfall!";
          verb = "entfällt";
          break;
        case "a":
          titel = "Änderung";
          verb = "wurde geändert";
          break;
      }

      event.waitUntil(
        self.registration.showNotification(titel, {
          tag: "vplan",
          body: "Ein Stunde " + verb,
          actions: [
            { action: "explore", title: "Vertretungsplan öffnen" },
            { action: "close", title: "Schließen" },
          ],
        })
      );
    }
  } catch (err) {
    console.error(err);
    console.log(event, event.data.text());
  }
});

self.addEventListener("notificationclick", event => {
  event.notification.close();
  if (event.action !== "close") {
    event.waitUntil(
      clients
        .matchAll({
          type: "window",
        })
        .then(function (clientList) {
          for (var i = 0; i < clientList.length; i++) {
            var client = clientList[i];
            if (client.url == "/Schulhof/Nutzerkonto" && "focus" in client) return client.focus();
          }
          if (clients.openWindow) return clients.openWindow("/Schulhof/Nutzerkonto");
        })
    );
  }
});
