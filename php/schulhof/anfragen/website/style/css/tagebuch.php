// HELL;
.cms_tagebuch_leistungsmessung {color: @h_haupt_meldungfehlerakzent;}

.cms_waehlbar:hover {
  cursor:pointer;
}

.cms_tagebuch_bemerkungen {
  display: block;
  margin-top: 10px;
}

.cms_tagebuch_negativ, .cms_tagebuch_positiv, .cms_tagebuch_entschuldigt, .cms_tagebuch_unentschuldigt, .cms_tagebuch_unbearbeitet {
  border-radius: 3px;
  display: inline-block;
  padding: 5px;
  margin-right: 2px;
  margin-bottom: 2px;
}

.cms_tagebuch_klein {
  font-size: 70%;
  color: @h_haupt_schriftfarbepositiv;
}

.cms_tagebuch_negativ .cms_tagebuch_klein, .cms_tagebuch_positiv .cms_tagebuch_klein {
  color: @h_haupt_schriftfarbenegativ;
}

.cms_tagebuch_unbearbeitet {
  border: 1px solid @h_haupt_abstufung2;
  color: @h_haupt_schriftfarbepositiv;
}
.cms_tagebuch_entschuldigt {
  border: 1px solid @h_haupt_meldungerfolgakzent;
  color: @h_haupt_meldungerfolgakzent;
}
.cms_tagebuch_unentschuldigt {
  border: 1px solid @h_haupt_meldungfehlerakzent;
  color: @h_haupt_meldungfehlerakzent;
}

.cms_tagebuch_negativ, .cms_tagebuch_positiv {
  color: @h_haupt_schriftfarbenegativ;
}

.cms_tagebuch_negativ {
  border: 1px solid @h_haupt_meldungfehlerakzent;
  background-color: @h_haupt_meldungfehlerakzent;
}

.cms_tagebuch_positiv {
  border: 1px solid @h_haupt_meldungerfolgakzent;
  background-color: @h_haupt_meldungerfolgakzent;
}

// DUNKEL;
.cms_tagebuch_leistungsmessung {color: @d_haupt_meldungfehlerakzent;}

.cms_tagebuch_klein {
  color: @d_haupt_schriftfarbepositiv;
}

.cms_tagebuch_negativ .cms_tagebuch_klein, .cms_tagebuch_positiv .cms_tagebuch_klein {
  color: @d_haupt_schriftfarbenegativ;
}

.cms_tagebuch_unbearbeitet {
  border: 1px solid @d_haupt_abstufung2;
  color: @d_haupt_schriftfarbepositiv;
}
.cms_tagebuch_entschuldigt {
  border: 1px solid @d_haupt_meldungerfolgakzent;
  color: @d_haupt_meldungerfolgakzent;
}
.cms_tagebuch_unentschuldigt {
  border: 1px solid @d_haupt_meldungfehlerakzent;
  color: @d_haupt_meldungfehlerakzent;
}

.cms_tagebuch_negativ, .cms_tagebuch_positiv {
  color: @d_haupt_schriftfarbenegativ;
}

.cms_tagebuch_negativ {
  border: 1px solid @d_haupt_meldungfehlerakzent;
  background-color: @d_haupt_meldungfehlerakzent;
}

.cms_tagebuch_positiv {
  border: 1px solid @d_haupt_meldungerfolgakzent;
  background-color: @d_haupt_meldungerfolgakzent;
}
