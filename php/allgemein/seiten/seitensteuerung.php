<?php
include_once("php/allgemein/funktionen/brotkrumen.php");

$ausnahme = false;
$CMS_MONATELINK = "(Januar|Februar|März|April|Mai|Juni|Juli|August|September|Oktober|November|Dezember)";

if ($CMS_URL[0] == 'Website') {
  include_once("php/website/seiten/initial.php");
  // WEBSITE
  $CMS_VERFUEGBARE_SEITEN['Website/Datenschutz']                                        = 'php/website/seiten/pflicht/datenschutz.php';
  $CMS_VERFUEGBARE_SEITEN['Website/Impressum']                                          = 'php/website/seiten/pflicht/impressum.php';
  if(preg_match("/^Website\/Newsletter_abbestellen\/[a-zA-Z0-9]{64}$/", $CMS_URLGANZ))
    {include("php/website/seiten/newsletter/abbestellen.php"); $ausnahme = true;}
  $CMS_VERFUEGBARE_SEITEN['Website/Feedback']                                           = 'php/website/seiten/feedback/geben.php';
  $CMS_VERFUEGBARE_SEITEN['Website/Feedback/Danke!']                                    = 'php/website/seiten/feedback/danke.php';
  if (preg_match("/^Website\/(Termine|Galerien|Blog)$/", $CMS_URLGANZ))
    {cms_seite_aus_schulhof($dbs); $ausnahme = true;}
  if (preg_match("/^Website\/(Termine|Galerien|Blog)\/[0-9]{4}$/", $CMS_URLGANZ))
    {cms_seite_aus_schulhof($dbs); $ausnahme = true;}
  if (preg_match("/^Website\/(Termine|Galerien|Blog)\/[0-9]{4}\/$CMS_MONATELINK$/", $CMS_URLGANZ))
    {cms_seite_aus_schulhof($dbs); $ausnahme = true;}
  if (preg_match("/^Website\/(Termine|Galerien|Blog)\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{2}$/", $CMS_URLGANZ))
    {cms_seite_aus_schulhof($dbs); $ausnahme = true;}
  if (preg_match("/^Website\/(Termine|Galerien|Blog)\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{2}\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {cms_seite_aus_schulhof($dbs); $ausnahme = true;}
  if (preg_match("/^Website$/", $CMS_URLGANZ))
    {cms_seite_ausgeben($dbs); $ausnahme = true;}
  if (preg_match("/^Website\/(Seiten|Bearbeiten)\/(Aktuell|Neu|Alt)(\/$CMS_LINKMUSTER)*$/", $CMS_URLGANZ))
    {cms_seite_ausgeben($dbs); $ausnahme = true;}
  if (preg_match("/^Website\/Ferien(\/[0-9]{4}){0,1}$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ] = 'php/website/seiten/ferien/ferien.php';}
  $CMS_VERFUEGBARE_SEITEN['Website/Voranmeldung']                                         = 'php/website/seiten/schulanmeldung/info.php';
  $CMS_VERFUEGBARE_SEITEN['Website/Voranmeldung/Information']                             = 'php/website/seiten/schulanmeldung/info.php';
  $CMS_VERFUEGBARE_SEITEN['Website/Voranmeldung/Schülerdaten']                            = 'php/website/seiten/schulanmeldung/schueler.php';
  $CMS_VERFUEGBARE_SEITEN['Website/Voranmeldung/Ansprechpartner']                         = 'php/website/seiten/schulanmeldung/ansprechpartner.php';
  $CMS_VERFUEGBARE_SEITEN['Website/Voranmeldung/Zusammenfassung']                         = 'php/website/seiten/schulanmeldung/zusammenfassung.php';
  $CMS_VERFUEGBARE_SEITEN['Website/Voranmeldung/Fertig']                                  = 'php/website/seiten/schulanmeldung/fertig.php';
  $CMS_VERFUEGBARE_SEITEN['Website/Fehler']                                               = 'php/website/seiten/fehler/404.php';
  $CMS_VERFUEGBARE_SEITEN['Website/Fehler/301']                                           = 'php/website/seiten/fehler/301.php';
  $CMS_VERFUEGBARE_SEITEN['Website/Fehler/302']                                           = 'php/website/seiten/fehler/302.php';
  $CMS_VERFUEGBARE_SEITEN['Website/Fehler/403']                                           = 'php/website/seiten/fehler/403.php';
  $CMS_VERFUEGBARE_SEITEN['Website/Fehler/404']                                           = 'php/website/seiten/fehler/404.php';
  $CMS_VERFUEGBARE_SEITEN['Website/Fehler/500']                                           = 'php/website/seiten/fehler/500.php';
}
else if ($CMS_URL[0] == 'Problembehebung') {
  // PROBLEMBEHEBUNG
  $CMS_VERFUEGBARE_SEITEN['Problembehebung'] = 'php/schulhof/seiten/aufgaben/geraete/keinticket.php';
  if (preg_match("/^Problembehebung\/Ticket_[-a-zA-Z0-9]+$/", $CMS_URLGANZ)) {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ] = 'php/schulhof/seiten/aufgaben/geraete/ticketloesung.php';}
}
else if ($CMS_URL[0] == 'Intern') {
  // PROBLEMBEHEBUNG
  $CMS_VERFUEGBARE_SEITEN['Intern'] = 'php/lehrerzimmer/seiten/intern/intern.php';
  if (preg_match("/^Intern\/Schülervertretungsplan(\/[-a-zA-Z0-9]+){0,1}$/", $CMS_URLGANZ)) {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ] = 'php/lehrerzimmer/seiten/intern/schuelervertretungsplan.php';}
  if (preg_match("/^Intern\/Lehrervertretungsplan(\/[-a-zA-Z0-9]+){0,1}$/", $CMS_URLGANZ)) {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ] = 'php/lehrerzimmer/seiten/intern/lehrervertretungsplan.php';}
}
else if ($CMS_URL[0] == 'App') {
  $CMS_VERFUEGBARE_SEITEN['App']                                                     = 'php/app/seiten/hauptmenue.php';
  $CMS_VERFUEGBARE_SEITEN['App/Mein_Tag']                                            = 'php/app/seiten/meintag.php';
  $CMS_VERFUEGBARE_SEITEN['App/Postfach']                                            = 'php/app/seiten/postfach/posteingang.php';
  $CMS_VERFUEGBARE_SEITEN['App/Postfach/Posteingang']                                = 'php/app/seiten/postfach/posteingang.php';
  $CMS_VERFUEGBARE_SEITEN['App/Postfach/Entwürfe']                                   = 'php/app/seiten/postfach/entwuerfe.php';
  $CMS_VERFUEGBARE_SEITEN['App/Postfach/Postausgang']                                = 'php/app/seiten/postfach/postausgang.php';
  $CMS_VERFUEGBARE_SEITEN['App/Postfach/Papierkorb']                                 = 'php/app/seiten/postfach/papierkorb.php';
  $CMS_VERFUEGBARE_SEITEN['App/Postfach/Neue_Nachricht']                             = 'php/app/seiten/postfach/neuenachricht.php';
  $CMS_VERFUEGBARE_SEITEN['App/Postfach/Nachricht_lesen']                            = 'php/app/seiten/postfach/nachrichtlesen.php';
  $CMS_VERFUEGBARE_SEITEN['App/Probleme_melden']                                     = 'php/app/seiten/problememelden/probleme.php';
  $CMS_VERFUEGBARE_SEITEN['App/Probleme_melden/Geräte']                              = 'php/app/seiten/problememelden/geraete.php';
  $CMS_VERFUEGBARE_SEITEN['App/Probleme_melden/Räume']                               = 'php/app/seiten/problememelden/geraete.php';
  $CMS_VERFUEGBARE_SEITEN['App/Probleme_melden/Leihgeräte']                          = 'php/app/seiten/problememelden/geraete.php';
  $CMS_VERFUEGBARE_SEITEN['App/Probleme_melden/Hausmeisteraufträge']                 = 'php/app/seiten/problememelden/hausmeister.php';
  if (preg_match("/^App\/Probleme_melden\/Räume\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                           = 'php/app/seiten/problememelden/raeume.php';}
  if (preg_match("/^App\/Probleme_melden\/Leihgeräte\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                           = 'php/app/seiten/problememelden/leihgeraete.php';}
}
else if ($CMS_URL[0] == 'Schulhof') {
  include_once("php/schulhof/anfragen/verwaltung/gruppen/initial.php");
  // SCHULHOF
  $CMS_VERFUEGBARE_SEITEN['Schulhof']                                                     = 'php/schulhof/seiten/nutzerkonto/nutzerkonto.php';
  // Anmeldung
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Anmeldung']                                           = 'php/schulhof/seiten/nutzerkonto/anmelden.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Anmeldung/Bis_bald!']                                 = 'php/schulhof/seiten/nutzerkonto/anmelden.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Anmeldung/Automatische_Abmeldung']                    = 'php/schulhof/seiten/nutzerkonto/anmelden.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Anmeldung/Zugeschickt!']                              = 'php/schulhof/seiten/nutzerkonto/anmelden.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Anmeldung/Registriert!']                              = 'php/schulhof/seiten/nutzerkonto/anmelden.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Passwort_vergessen']                                  = 'php/schulhof/seiten/nutzerkonto/passwortvergessen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Registrieren']                                        = 'php/schulhof/seiten/nutzerkonto/registrieren.php';

  // Nutzerkonto
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto']                                         = 'php/schulhof/seiten/nutzerkonto/nutzerkonto.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Mein_Profil']                             = 'php/schulhof/seiten/nutzerkonto/profildaten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Mein_Profil/Gespeicherte_Daten']          = 'php/schulhof/seiten/nutzerkonto/gespeichertedaten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Mein_Profil/Meine_Rechte']                = 'php/schulhof/seiten/nutzerkonto/meinerechte.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Mein_Profil/Nutzerkonto_bearbeiten']      = 'php/schulhof/seiten/nutzerkonto/benutzerkonto.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Mein_Profil/Passwort_ändern']             = 'php/schulhof/seiten/nutzerkonto/passwort.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Mein_Profil/Identitätsdiebstahl']         = 'php/schulhof/seiten/nutzerkonto/identitaetsdiebstahl.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Mein_Stundenplan']                        = 'php/schulhof/seiten/nutzerkonto/stundenplan.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Einstellungen']                           = 'php/schulhof/seiten/nutzerkonto/einstellungen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Postfach']                                = 'php/schulhof/seiten/nutzerkonto/postfach/posteingang.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Postfach/Neue_Nachricht']                 = 'php/schulhof/seiten/nutzerkonto/postfach/neuenachricht.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Postfach/Posteingang']                    = 'php/schulhof/seiten/nutzerkonto/postfach/posteingang.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Postfach/Entwürfe']                       = 'php/schulhof/seiten/nutzerkonto/postfach/entwuerfe.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Postfach/Postausgang']                    = 'php/schulhof/seiten/nutzerkonto/postfach/postausgang.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Postfach/Papierkorb']                     = 'php/schulhof/seiten/nutzerkonto/postfach/papierkorb.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Postfach/Nachricht_lesen']                = 'php/schulhof/seiten/nutzerkonto/postfach/nachrichtlesen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Postfach/Tags']                           = 'php/schulhof/seiten/nutzerkonto/postfach/tags.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Postfach/Tags/Tag_bearbeiten']            = 'php/schulhof/seiten/nutzerkonto/postfach/tagbearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Postfach/Tags/Neuen_Tag_anlegen']         = 'php/schulhof/seiten/nutzerkonto/postfach/neuertag.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Postfach/Signatur']                       = 'php/schulhof/seiten/nutzerkonto/postfach/signatur.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Probleme_melden']                         = 'php/schulhof/seiten/nutzerkonto/problememelden.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Favoriten']                               = 'php/schulhof/seiten/nutzerkonto/favoriten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Neuigkeiten']                             = 'php/schulhof/seiten/nutzerkonto/neuigkeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Nutzerkonto/Tagebuch']                                = 'php/schulhof/seiten/nutzerkonto/tagebuch.php';

  // Aufgaben
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Aufgaben']                                            = 'php/schulhof/seiten/aufgaben/aufgaben.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Aufgaben/Geräte_verwalten']                           = 'php/schulhof/seiten/aufgaben/geraete/verwalten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Aufgaben/Geräte_verwalten/Problembericht_bearbeiten'] = 'php/schulhof/seiten/aufgaben/geraete/problemberichtbearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Aufgaben/Identitätsdiebstähle_behandeln']             = 'php/schulhof/seiten/aufgaben/identitaetsdiebstahl/identitaetsdiebstahl.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Aufgaben/Registrierungen']                            = 'php/schulhof/seiten/aufgaben/registrierungen/registrierungen.php';
  // Hausmeister
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Hausmeister']                                         = 'php/schulhof/seiten/hausmeister/hausmeister.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Hausmeister/Aufträge']                                = 'php/schulhof/seiten/hausmeister/auftraege.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Hausmeister/Aufträge/Details']                        = 'php/schulhof/seiten/hausmeister/details.php';
  // Listen
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Listen']                                              = 'php/schulhof/seiten/listen/listen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Listen/Personen']                                     = 'php/schulhof/seiten/listen/listenpersonen.php';
  if (preg_match("/^Schulhof\/Listen\/Personen\/(Lehrer|Schüler|Eltern|Verwaltung|Externe|Schülervertreter|Elternvertreter)/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/listen/personenlisten.php';}
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Listen/Gruppen']                                      = 'php/schulhof/seiten/listen/listengruppen.php';
  if (preg_match("/^Schulhof\/Listen\/Gruppen\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/listen/listengruppenart.php';}
  if (preg_match("/^Schulhof\/Listen\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                              = 'php/schulhof/seiten/listen/listengruppenschuljahr.php';}
  if (preg_match("/^Schulhof\/Listen\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                              = 'php/schulhof/seiten/listen/gruppenlisten.php';}

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Listen/Verwaltungspersonal']                          = 'php/schulhof/seiten/listen/gruppenlisten.php';
  // Pläne
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Pläne']                                               = 'php/schulhof/seiten/plaene/alle.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Pläne/Vertretungen']                                  = 'php/schulhof/seiten/plaene/vertretungen/alle.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Pläne/Vertretungen/Lehreransicht']                    = 'php/schulhof/seiten/plaene/vertretungen/lehrervertretungsplan.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Pläne/Vertretungen/Schüleransicht']                   = 'php/schulhof/seiten/plaene/vertretungen/schuelervertretungsplan.php';

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Pläne/Lehrer']                                        = 'php/schulhof/seiten/plaene/lehrer/alle.php';
  if (preg_match("/^Schulhof\/Pläne\/Lehrer\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/plaene/lehrer/anzeigen.php';}

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Pläne/Klassen']                                       = 'php/schulhof/seiten/plaene/klassen/alle.php';
  if (preg_match("/^Schulhof\/Pläne\/Klassen\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/plaene/klassen/anzeigen.php';}

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Pläne/Stufen']                                        = 'php/schulhof/seiten/plaene/stufen/alle.php';
  if (preg_match("/^Schulhof\/Pläne\/Stufen\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/plaene/stufen/anzeigen.php';}

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Pläne/Räume']                                         = 'php/schulhof/seiten/plaene/raeume/alle.php';
  if (preg_match("/^Schulhof\/Pläne\/Räume\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/plaene/raeume/anzeigen.php';}

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Pläne/Leihgeräte']                                    = 'php/schulhof/seiten/plaene/leihgeraete/alle.php';
  if (preg_match("/^Schulhof\/Pläne\/Leihgeräte\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/plaene/leihgeraete/anzeigen.php';}

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Dauerbrenner']                                        = 'php/schulhof/seiten/verwaltung/dauerbrenner/alle.php';
  if (preg_match("/^Schulhof\/Dauerbrenner\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/verwaltung/dauerbrenner/anzeigen.php';}

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Pinnwände']                                           = 'php/schulhof/seiten/verwaltung/pinnwaende/alle.php';
  if (preg_match("/^Schulhof\/Pinnwände\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/verwaltung/pinnwaende/anzeigen.php';}
  if (preg_match("/^Schulhof\/Pinnwände\/$CMS_LINKMUSTER\/Neuer_Anschlag$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/verwaltung/pinnwaende/anschlaege/neueranschlag.php';}
  if (preg_match("/^Schulhof\/Pinnwände\/$CMS_LINKMUSTER\/Anschlag_bearbeiten$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/verwaltung/pinnwaende/anschlaege/anschlagbearbeiten.php';}

  // Verwaltung
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung']                                          = 'php/schulhof/seiten/verwaltung/verwaltung.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen']                                 = 'php/schulhof/seiten/verwaltung/personen/personen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/Neue_Person_anlegen']             = 'php/schulhof/seiten/verwaltung/personen/neueperson.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/Neues_Nutzerkonto_anlegen']       = 'php/schulhof/seiten/verwaltung/personen/neuesnutzerkonto.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/Nutzerkonto_bearbeiten']          = 'php/schulhof/seiten/verwaltung/personen/benutzerkonto.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/Bearbeiten']                      = 'php/schulhof/seiten/verwaltung/personen/persoenlichedaten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/Rollen_und_Rechte']               = 'php/schulhof/seiten/verwaltung/personen/rollenundrechte.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/Lehrerkürzel_ändern']             = 'php/schulhof/seiten/verwaltung/personen/lehrerkuerzel.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/Details']                         = 'php/schulhof/seiten/verwaltung/personen/detailspersonen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/Einstellungen_des_Nutzerkontos']  = 'php/schulhof/seiten/verwaltung/personen/einstellungendesnutzerkontos.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/Schüler_und_Eltern_verknüpfen']   = 'php/schulhof/seiten/verwaltung/personen/schuelereltern.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/Stundenplan']                     = 'php/schulhof/seiten/verwaltung/personen/stundenplan.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/IDs_importieren']                 = 'php/schulhof/seiten/verwaltung/personen/idsimportieren.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/IDs_bearbeiten']                  = 'php/schulhof/seiten/verwaltung/personen/idsbearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/Kurszuordnung_zurücksetzen']      = 'php/schulhof/seiten/verwaltung/personen/kurszuordnungzurueck.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/Kurszuordnung_importieren']       = 'php/schulhof/seiten/verwaltung/personen/kurszuordnungimportieren.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/Kurszuordnung_Lehrer_und_Schüler'] = 'php/schulhof/seiten/verwaltung/personen/kurszuordnungklasseregelunterricht.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Personen/Nicht_zugeordnet_löschen']        = 'php/schulhof/seiten/verwaltung/personen/nichtzugeordnetloeschen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Bedingte_Rechte']                          = 'php/schulhof/seiten/verwaltung/bedingte_rechte.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Rollen']                                   = 'php/schulhof/seiten/verwaltung/rollen/rollen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Rollen/Rolle_bearbeiten']                  = 'php/schulhof/seiten/verwaltung/rollen/rollebearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Rollen/Neue_Rolle_anlegen']                = 'php/schulhof/seiten/verwaltung/rollen/neuerolle.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Gruppen']                                  = 'php/schulhof/seiten/verwaltung/gruppen/gruppen.php';
  foreach ($CMS_GRUPPEN as $g) {
    $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Gruppen/'.cms_textzulink($g)]                        = 'php/schulhof/seiten/verwaltung/gruppen/spezifisch/alle.php';
    $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Gruppen/'.cms_textzulink($g).'/Gruppe_bearbeiten']   = 'php/schulhof/seiten/verwaltung/gruppen/spezifisch/bearbeiten.php';
    $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Gruppen/'.cms_textzulink($g).'/Neue_Gruppe_anlegen'] = 'php/schulhof/seiten/verwaltung/gruppen/spezifisch/neu.php';
  }
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Schuljahre']                                = 'php/schulhof/seiten/verwaltung/schuljahre/schuljahre.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Schuljahre/Neues_Schuljahr_anlegen']        = 'php/schulhof/seiten/verwaltung/schuljahre/neuesschuljahr.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Schuljahre/Schuljahr_bearbeiten']           = 'php/schulhof/seiten/verwaltung/schuljahre/schuljahrbearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Schuljahre/Verantwortlichkeiten_festlegen'] = 'php/schulhof/seiten/verwaltung/schuljahre/verantwortlichkeiten.php';

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Dauerbrenner']                             = 'php/schulhof/seiten/verwaltung/dauerbrenner/dauerbrenner.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Dauerbrenner/Neuen_Dauerbrenner_anlegen']  = 'php/schulhof/seiten/verwaltung/dauerbrenner/neuerdauerbrenner.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Dauerbrenner/Dauerbrenner_bearbeiten']     = 'php/schulhof/seiten/verwaltung/dauerbrenner/dauerbrennerbearbeiten.php';

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Pinnwände']                                = 'php/schulhof/seiten/verwaltung/pinnwaende/pinnwaende.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Pinnwände/Neue_Pinnwand_anlegen']          = 'php/schulhof/seiten/verwaltung/pinnwaende/neuepinnwand.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Pinnwände/Pinnwand_bearbeiten']            = 'php/schulhof/seiten/verwaltung/pinnwaende/pinnwandbearbeiten.php';

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Profile']                                             = 'php/schulhof/seiten/personensuche/personenprofil.php';

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung']                                  = 'php/schulhof/seiten/verwaltung/verwaltung.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Schuljahrfabrik']                  = 'php/schulhof/seiten/verwaltung/schuljahrfabrik/grundlagen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Schuljahrfabrik/Grundlagen']         = 'php/schulhof/seiten/verwaltung/schuljahrfabrik/grundlagen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Schuljahrfabrik/Profile']            = 'php/schulhof/seiten/verwaltung/schuljahrfabrik/profile.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Schuljahrfabrik/Schüler_in_Gruppen'] = 'php/schulhof/seiten/verwaltung/schuljahrfabrik/gruppenschueler.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Schuljahrfabrik/Klassenkurse']       = 'php/schulhof/seiten/verwaltung/schuljahrfabrik/klassenkurse.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Schuljahrfabrik/Stufenkurse']        = 'php/schulhof/seiten/verwaltung/schuljahrfabrik/stufenkurse.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Schuljahrfabrik/Personen_in_Kursen'] = 'php/schulhof/seiten/verwaltung/schuljahrfabrik/kursepersonen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Schuljahrfabrik/Lehraufträge']       = 'php/schulhof/seiten/verwaltung/schuljahrfabrik/lehrauftraege.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Schuljahrfabrik']                  = 'php/schulhof/seiten/verwaltung/schuljahrfabrik/schuljahrfabrik.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Zeiträume']                            = 'php/schulhof/seiten/verwaltung/zeitraeume/zeitraeume.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Zeiträume/Neuen_Zeitraum_anlegen']     = 'php/schulhof/seiten/verwaltung/zeitraeume/neuerzeitraum.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Zeiträume/Zeitraum_bearbeiten']        = 'php/schulhof/seiten/verwaltung/zeitraeume/zeitraumbearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Zeiträume/Zeitraum_rythmisieren']      = 'php/schulhof/seiten/verwaltung/zeitraeume/zeitraumrythmisieren.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Zeiträume/Zeitraum_klonen']            = 'php/schulhof/seiten/verwaltung/zeitraeume/zeitraumklonen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Zeiträume/Stundenplanung_importieren'] = 'php/schulhof/seiten/verwaltung/zeitraeume/stundenplanungimportieren.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Profile']                          = 'php/schulhof/seiten/verwaltung/profile/profile.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Profile/Neues_Profil_anlegen']     = 'php/schulhof/seiten/verwaltung/profile/neuesprofil.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Profile/Profil_bearbeiten']        = 'php/schulhof/seiten/verwaltung/profile/profilbearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Schienen']                         = 'php/schulhof/seiten/verwaltung/schienen/schienen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Schienen/Neue_Schiene_anlegen']    = 'php/schulhof/seiten/verwaltung/schienen/neueschiene.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Schienen/Schiene_bearbeiten']      = 'php/schulhof/seiten/verwaltung/schienen/schienebearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Fächer']                           = 'php/schulhof/seiten/verwaltung/faecher/faecher.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Fächer/Neues_Fach_anlegen']        = 'php/schulhof/seiten/verwaltung/faecher/neuesfach.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Fächer/Fächer_importieren']        = 'php/schulhof/seiten/verwaltung/faecher/importieren.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Fächer/Fach_bearbeiten']           = 'php/schulhof/seiten/verwaltung/faecher/fachbearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Stundenplanung']                   = 'php/schulhof/seiten/verwaltung/stundenplanung/stundenplanung.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Stunden_und_Tagebücher_erzeugen']  = 'php/schulhof/seiten/verwaltung/stundenplanung/stundenerzeugen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Ausplanungen']                     = 'php/schulhof/seiten/verwaltung/vertretungsplanung/ausplanungen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Planung/Vertretungsplanung']               = 'php/schulhof/seiten/verwaltung/vertretungsplanung/vertretungsplanung.php';

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Räume']                                    = 'php/schulhof/seiten/verwaltung/raeume/raeume.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Räume/Neuen_Raum_anlegen']                 = 'php/schulhof/seiten/verwaltung/raeume/neuerraum.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Räume/Raum_bearbeiten']                    = 'php/schulhof/seiten/verwaltung/raeume/raumbearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Leihgeräte']                               = 'php/schulhof/seiten/verwaltung/leihgeraete/leihgeraete.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Leihgeräte/Neue_Leihgeräte_anlegen']       = 'php/schulhof/seiten/verwaltung/leihgeraete/neueleihgeraete.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Leihgeräte/Leihgeräte_bearbeiten']         = 'php/schulhof/seiten/verwaltung/leihgeraete/leihgeraetebearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Allgemeine_Einstellungen']                 = 'php/schulhof/seiten/verwaltung/allgemeineeinstellungen/allgemeineeinstellungen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Schulnetze']                               = 'php/schulhof/seiten/verwaltung/schulnetze/schulnetze.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/VPN']                                      = 'php/schulhof/seiten/verwaltung/vpn/vpn.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Zulässige_Dateien']                        = 'php/schulhof/seiten/verwaltung/zulaessigedateien/zulaessigedateien.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Schuldetails']                             = 'php/schulhof/seiten/verwaltung/schuldetails/schuldetails.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Schulanmeldung']                           = 'php/schulhof/seiten/verwaltung/schulanmeldung/schulanmeldung.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Schulanmeldung/Einstellungen']             = 'php/schulhof/seiten/verwaltung/schulanmeldung/einstellungen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Schulanmeldung/Neue_Anmeldung']            = 'php/schulhof/seiten/verwaltung/schulanmeldung/neueanmeldung.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Schulanmeldung/Anmeldung_bearbeiten']      = 'php/schulhof/seiten/verwaltung/schulanmeldung/bearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Schulanmeldung/Exportieren']               = 'php/schulhof/seiten/verwaltung/schulanmeldung/exportieren.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Ferien']                                   = 'php/schulhof/seiten/verwaltung/ferien/ferien.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Ferien/Neuer_Ferientermin']                = 'php/schulhof/seiten/verwaltung/ferien/neuerferientermin.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Verwaltung/Ferien/Ferientermin_bearbeiten']           = 'php/schulhof/seiten/verwaltung/ferien/ferienterminbearbeiten.php';
  if (preg_match("/^Schulhof\/Ferien(\/[0-9]{4}){0,1}$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/website/seiten/ferien/ferien.php';}

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Hilfe/VPN']                                           = 'php/schulhof/seiten/hilfe/vpn/vpn.php';

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Gruppen']                                             = 'php/schulhof/seiten/gruppen/allegruppen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Gruppen/Schuljahrübergreifend']                       = 'php/schulhof/seiten/gruppen/schuljahruebersicht.php';
  if (preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/gruppen/schuljahruebersicht.php';}
  if (preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/gruppen/schuljahrspartenuebersicht.php';}
  if (preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/gruppen/ansicht.php';}
  if (preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Blog(\/[0-9]{4}(\/$CMS_MONATELINK(\/[0-9]{2}){0,1}){0,1}){0,1}$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/gruppen/blog.php';}
  if (preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Blog\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{2}\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/gruppen/blogansicht.php';}
  if (preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Blog\/Neuer_Blogeintrag$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/gruppen/neuerblogeintrag.php';}
  if (preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Blog\/Blogeintrag_bearbeiten/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/gruppen/bearbeitenblogeintrag.php';}
  if (preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Beschlüsse(\/[0-9]{4}){0,1}$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/gruppen/beschluesse.php';}
  if (preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Termine(\/[0-9]{4}(\/$CMS_MONATELINK(\/[0-9]{2}){0,1}){0,1}){0,1}$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/gruppen/kalender.php';}
  if (preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Termine\/Neuer_Termin$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/gruppen/neuertermin.php';}
  if (preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Termine\/Termin_bearbeiten/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/gruppen/bearbeitentermin.php';}
  if (preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Termine\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{2}\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/gruppen/terminansicht.php';}

  if (preg_match("/^Schulhof\/Termine(\/[0-9]{4}(\/$CMS_MONATELINK(\/[0-9]{2}){0,1}){0,1}){0,1}$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/termine/kalender.php';}
  if (preg_match("/^Schulhof\/Termine\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{2}\/$CMS_LINKMUSTER$/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/termine/terminansicht.php';}
  if (preg_match("/^Schulhof\/Blog(\/[0-9]{4}(\/$CMS_MONATELINK(\/[0-9]{2}){0,1}){0,1}){0,1}/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/blogeintraege/blog.php';}
  if (preg_match("/^Schulhof\/Blog\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{1,2}\/$CMS_LINKMUSTER/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/blogeintraege/blogansicht.php';}
  if (preg_match("/^Schulhof\/Galerien(\/[0-9]{4}(\/$CMS_MONATELINK(\/[0-9]{2}){0,1}){0,1}){0,1}/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/galerien/galerie.php';}
  if (preg_match("/^Schulhof\/Galerien\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{1,2}\/$CMS_LINKMUSTER/", $CMS_URLGANZ))
    {$CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]                                                = 'php/schulhof/seiten/galerien/galerieansicht.php';}

  // Website
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website']                                             = 'php/schulhof/seiten/website/website.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Seiten']                                      = 'php/schulhof/seiten/website/seiten/seiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Seiten/Neue_Seite_anlegen']                   = 'php/schulhof/seiten/website/seiten/neueseite.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Seiten/Seite_bearbeiten']                     = 'php/schulhof/seiten/website/seiten/seitebearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Hauptnavigationen']                           = 'php/schulhof/seiten/website/hauptnavigationen/hauptnavigationen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Dateien']                                     = 'php/schulhof/seiten/website/dateien/dateien.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Titelbilder']                                 = 'php/schulhof/seiten/website/titelbilder/titelbilder.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Auszeichnungen']                              = 'php/schulhof/seiten/website/auszeichnungen/auszeichnungen.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Auszeichnungen/Auszeichnung_anlegen']         = 'php/schulhof/seiten/website/auszeichnungen/neueauszeichnung.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Auszeichnungen/Auszeichnung_bearbeiten']      = 'php/schulhof/seiten/website/auszeichnungen/auszeichnungbearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Termine']                                     = 'php/schulhof/seiten/website/termine/termine.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Termine/Neuer_Termin']                        = 'php/schulhof/seiten/website/termine/neuertermin.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Termine/Termin_bearbeiten']                   = 'php/schulhof/seiten/website/termine/terminbearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Aufgaben/Termine_genehmigen']                         = 'php/schulhof/seiten/website/termine/genehmigungscenter.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Blogeinträge']                                = 'php/schulhof/seiten/website/blogeintraege/blogeintraege.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Blogeinträge/Neuer_Blogeintrag']              = 'php/schulhof/seiten/website/blogeintraege/neuerblogeintrag.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Blogeinträge/Blogeintrag_bearbeiten']         = 'php/schulhof/seiten/website/blogeintraege/blogeintragbearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Aufgaben/Blogeinträge_genehmigen']                    = 'php/schulhof/seiten/website/blogeintraege/genehmigungscenter.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Galerien']                                    = 'php/schulhof/seiten/website/galerien/galerien.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Galerien/Neue_Galerie']                       = 'php/schulhof/seiten/website/galerien/neuegalerie.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Galerien/Galerie_bearbeiten']                 = 'php/schulhof/seiten/website/galerien/galeriebearbeiten.php';

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Newsletter']                                  = 'php/schulhof/seiten/website/newsletter/newsletter.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Newsletter/Neuer_Newsletter']                 = 'php/schulhof/seiten/website/newsletter/neuernewsletter.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Newsletter/Newsletter_bearbeiten']            = 'php/schulhof/seiten/website/newsletter/newsletterbearbeiten.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Newsletter/Newsletter_ansehen']               = 'php/schulhof/seiten/website/newsletter/newsletteransehen.php';

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Aufgaben/Galerien_genehmigen']                        = 'php/schulhof/seiten/website/galerien/genehmigungscenter.php';

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Aufgaben/Auffälliges']                                = 'php/schulhof/seiten/auffaelliges/liste.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Aufgaben/Auffälliges/Details']                        = 'php/schulhof/seiten/auffaelliges/details.php';

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Besucherstatistiken']                         = 'php/schulhof/seiten/website/besucherstatistiken/besucherstatistiken.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Besucherstatistiken/Schulhof']                = 'php/schulhof/seiten/website/besucherstatistiken/besucherstatistikenSchulhof.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Besucherstatistiken/Website']                 = 'php/schulhof/seiten/website/besucherstatistiken/besucherstatistikenGruppeWebsite.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Besucherstatistiken/Website/Website']         = 'php/schulhof/seiten/website/besucherstatistiken/website/besucherstatistikenWebsite.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Besucherstatistiken/Website/Blogeinträge']    = 'php/schulhof/seiten/website/besucherstatistiken/website/besucherstatistikenBlogeintraege.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Besucherstatistiken/Website/Galerien']        = 'php/schulhof/seiten/website/besucherstatistiken/website/besucherstatistikenGalerien.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Besucherstatistiken/Website/Termine']         = 'php/schulhof/seiten/website/besucherstatistiken/website/besucherstatistikenTermine.php';

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Feedback']                                    = 'php/schulhof/seiten/website/feedback/liste.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Feedback/Details']                            = 'php/schulhof/seiten/website/feedback/details.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Fehlermeldungen']                             = 'php/schulhof/seiten/website/fehlermeldungen/liste.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Fehlermeldungen/Details']                     = 'php/schulhof/seiten/website/fehlermeldungen/details.php';

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Weiterleiten']                                = 'php/schulhof/seiten/website/weiterleiten/liste.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Weiterleiten/Neu']                            = 'php/schulhof/seiten/website/weiterleiten/neu.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Website/Weiterleiten/Details']                        = 'php/schulhof/seiten/website/weiterleiten/bearbeiten.php';

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Aufgaben/Chatmeldungen']                              = 'php/schulhof/seiten/verwaltung/chatmeldungen/liste.php';

  $CMS_VERFUEGBARE_SEITEN['Schulhof/Fehler']                                              = 'php/schulhof/seiten/fehler/404.php';
  $CMS_VERFUEGBARE_SEITEN['Schulhof/Fehler/404']                                          = 'php/schulhof/seiten/fehler/404.php';
}

// AUSNAHMEN UND FEHLERMELDUNGEN
if (!$ausnahme) {
  if (isset($CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ])) {include_once($CMS_VERFUEGBARE_SEITEN[$CMS_URLGANZ]);}
  else {
    if (isset($CMS_URL[0])) {
      if ($CMS_URL[0] == "Website") {cms_fehler("Website", "404");}
      else if ($CMS_URL[0] == "Problembehebung") {cms_fehler("Schulhof", "404");}
      else if ($CMS_URL[0] == "Schulhof") {cms_fehler("Schulhof", "404");}
      else if ($CMS_URL[0] == "Intern") {cms_fehler("Schulhof", "404");}
      else if ($CMS_URL[0] == "App") {include_once('php/app/seiten/404.php');}
    }
    else {cms_fehler("Website", "404");}
  }
}
?>
