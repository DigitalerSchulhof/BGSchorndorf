// HELL;

[class^="note-icon-"]:before, [class*=" note-icon-"]:before {
	display:inline-block;
font: normal normal normal 14px 'summernote';
	font-size: inherit;
-webkit-font-smoothing: antialiased;
	text-decoration: inherit;
text-rendering: auto;
text-transform: none;
vertical-align: middle;
speak: never;
-moz-osx-font-smoothing: grayscale;
}

.note-icon-align-center:before, .note-icon-align-indent:before, .note-icon-align-justify:before,
.note-icon-align-left:before, .note-icon-align-outdent:before, .note-icon-align-right:before,
.note-icon-align:before, .note-icon-arrow-circle-down:before, .note-icon-arrow-circle-left:before,
.note-icon-arrow-circle-right:before, .note-icon-arrow-circle-up:before, .note-icon-arrows-alt:before,
.note-icon-arrows-h:before, .note-icon-arrows-v:before, .note-icon-bold:before, .note-icon-caret:before,
.note-icon-chain-broken:before, .note-icon-circle:before, .note-icon-close:before, .note-icon-code:before,
.note-icon-col-after:before, .note-icon-col-before:before, .note-icon-col-remove:before,
.note-icon-eraser:before, .note-icon-font:before, .note-icon-frame:before, .note-icon-italic:before,
.note-icon-link:before, .note-icon-magic:before, .note-icon-menu-check:before, .note-icon-minus:before,
.note-icon-orderedlist:before, .note-icon-pencil:before, .note-icon-picture:before, .note-icon-question:before,
.note-icon-redo:before, .note-icon-row-above:before, .note-icon-row-below:before, .note-icon-row-remove:before,
.note-icon-special-character:before, .note-icon-square:before, .note-icon-strikethrough:before,
.note-icon-subscript:before, .note-icon-summernote:before, .note-icon-superscript:before,
.note-icon-table:before, .note-icon-text-height:before, .note-icon-trash:before,
.note-icon-underline:before, .note-icon-undo:before, .note-icon-unorderedlist:before,
.note-icon-video:before {
	display: inline-block;
	font-family: 'summernote';
	font-style: normal;
	font-weight: normal;
	text-decoration: inherit;
}

.note-icon-align-center:before {content: '\f101';}
.note-icon-align-indent:before {content: '\f102';}
.note-icon-align-justify:before {content: '\f103';}
.note-icon-align-left:before {content: '\f104';}
.note-icon-align-outdent:before {content: '\f105';}
.note-icon-align-right:before {content: '\f106';}
.note-icon-align:before {content: '\f107';}
.note-icon-arrow-circle-down:before {content: '\f108';}
.note-icon-arrow-circle-left:before {content: '\f109';}
.note-icon-arrow-circle-right:before {content: '\f10a';}
.note-icon-arrow-circle-up:before {content: '\f10b';}
.note-icon-arrows-alt:before {content: '\f10c';}
.note-icon-arrows-h:before {content: '\f10d';}
.note-icon-arrows-v:before {content: '\f10e';}
.note-icon-bold:before {content: '\f10f';}
.note-icon-caret:before {content: '\f110';}
.note-icon-chain-broken:before {content: '\f111';}
.note-icon-circle:before {content: '\f112';}
.note-icon-close:before {content: '×';}
.note-icon-code:before {content: '\f114';}
.note-icon-col-after:before {content: '\f115';}
.note-icon-col-before:before {content: '\f116';}
.note-icon-col-remove:before {content: '\f117';}
.note-icon-eraser:before {content: '\f118';}
.note-icon-font:before {content: '\f119';}
.note-icon-frame:before {content: '\f11a';}
.note-icon-italic:before {content: '\f11b';}
.note-icon-link:before {content: '\f11c';}
.note-icon-magic:before {content: '\f11d';}
.note-icon-menu-check:before {content: '\f11e';}
.note-icon-minus:before {content: '\f11f';}
.note-icon-orderedlist:before {content: '\f120';}
.note-icon-pencil:before {content: '\f121';}
.note-icon-picture:before {content: '\f122';}
.note-icon-question:before {content: '\f123';}
.note-icon-redo:before {content: '\f124';}
.note-icon-row-above:before {content: '\f125';}
.note-icon-row-below:before {content: '\f126';}
.note-icon-row-remove:before {content: '\f127';}
.note-icon-special-character:before {content: '\f128';}
.note-icon-square:before {content: '\f129';}
.note-icon-strikethrough:before {content: '\f12a';}
.note-icon-subscript:before {content: '\f12b';}
.note-icon-summernote:before {content: '\f12c';}
.note-icon-superscript:before {content: '\f12d';}
.note-icon-table:before {content: '\f12e';}
.note-icon-text-height:before {content: '\f12f';}
.note-icon-trash:before {content: '\f130';}
.note-icon-underline:before {content: '\f131';}
.note-icon-undo:before {content: '\f132';}
.note-icon-unorderedlist:before {content: '\f133';}
.note-icon-video:before {content: '\f134';}

.summernote-ui * {
	color: @h_haupt_schriftfarbepositiv;
	box-sizing: border-box;
}

.note-toolbar {
	padding: 10px 5px;
	border-bottom: 1px solid @h_haupt_abstufung1;
}

.note-btn-group {
	position: relative;
	display: inline-block;
	margin-right: 8px;
	border: 1px solid @h_haupt_abstufung1;
}

.note-btn-group > .note-btn-group {
	margin-right: 0;
	border: none;
}

.note-btn-group > .note-btn.focus, .note-btn-group > .note-btn-group.focus,
.note-btn-group > .note-btn.active, .note-btn-group > .note-btn-group.active {background-color: @h_haupt_abstufung1;}

.note-btn-group > .note-btn:first-child, .note-btn-group > .note-btn-group:first-child {margin-left: 0;}

.note-btn-group.open > .note-dropdown {display:block;}

.note-btn {
	display: inline-block;
	padding: 5px 10px;
	margin-bottom: 0;
	font-weight: normal;
	line-height: 1.4;
	color: @h_haupt_schriftfarbepositiv;
	text-align: center;
white-space: nowrap;
vertical-align: middle;
	cursor: pointer;
	background-color: @h_haupt_hintergrund;
	background-image: none;
	border: none;
outline: 0;
user-select: none;
touch-action: manipulation;
}

.note-btn:focus, .note-btn.focus, .note-btn:hover {
	color: @h_haupt_schriftfarbepositiv;
	background-color: @h_haupt_abstufung1;
	border-color: @h_haupt_abstufung1;
}

.note-btn.disabled:focus, .note-btn[disabled]:focus, fieldset[disabled] .note-btn:focus, .note-btn.disabled.focus, .note-btn[disabled].focus, fieldset[disabled] .note-btn.focus {
	background-color: @h_haupt_hintergrund;
	border-color: @h_haupt_hintergrund;
}

.note-btn:hover, .note-btn:focus, .note-btn.focus {
	color: @h_haupt_schriftfarbepositiv;
	text-decoration: none;
	background-color: @h_haupt_abstufung1;
outline: 0;
}

.note-btn:active, .note-btn.active {
	background-image: none;
outline: 0;
}

.note-btn.disabled, .note-btn[disabled], fieldset[disabled] .note-btn {
	cursor: not-allowed;
	opacity: .65;
}

.note-btn-primary {
	display: inline-block;
	border-radius: @button_rundeecken;
	padding: 3px 7px;
	margin-bottom: 2px;
	transition: 250ms ease-in-out;
	position: relative;
	background-color: @h_haupt_meldungerfolghinter;
	color: @h_button_schrift;
}

.note-btn-primary:hover, .note-btn-primary:focus, .note-btn-primary.focus {
	background-color: @h_haupt_meldungerfolgakzent;
	color: @h_button_schrifthover;
}

.note-btn-large {
	padding: 8px 34px;
	font-size: 16px;
}

.note-btn-block {
	display: block;
	width: 100%;
}

.note-btn-block+.note-btn-block {margin-top: 5px;}

input[type="submit"].note-btn-block, input[type="reset"].note-btn-block, input[type="button"].note-btn-block {width: 100%;}

button.close {
	border: 0;
	display: inline-block;
	border-radius: @button_rundeecken;
	padding: 1px 7px;
	margin-bottom: 2px;
	transition: 250ms ease-in-out;
	position: relative;
	position: absolute;
	right: 0px;
	top: -12px;
	background-color: @h_haupt_meldungfehlerhinter;
	color: @h_button_schrift;
	height: 20px;
	line-height: 100%;
}

button.close:hover {
	background-color: @h_haupt_meldungfehlerakzent;
	color: @h_button_schrifthover;
	cursor: pointer;
}

.note-dropdown {position: relative;}

.note-dropdown-menu {
	position: absolute;
	top: 100%;
	left: 0;
	z-index: 1000;
	display: none;
	float: left;
	min-width: 100px;
	padding: 5px;
	text-align: left;
	background: @h_haupt_hintergrund;
	border: 1px solid @h_haupt_abstufung1;
	background-clip: padding-box;
}

.note-btn-group.open .note-dropdown-menu {display: block;}

.note-dropdown-item {
	display: block;
}

.note-dropdown-item:hover {
	background-color: @h_haupt_abstufung1;
	transform: translate(0px) !important;
	display: block;
}

a.note-dropdown-item {
	color: @h_haupt_schriftfarbepositiv;
	text-decoration: none;
	padding: 5px;
}

.note-modal {
	position: absolute;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	z-index: 1050;
	display: none;
	overflow: hidden;
	opacity: 1;
}

.note-modal.open {display: block;}

.note-modal-content {
	position: relative;
	width: 500px;
	margin: 20px auto;
	background: @h_haupt_hintergrund;
outline: 0;
	background-clip: border-box;
	border-top-left-radius: 20px;
	border-bottom-left-radius: 20px;
	border-bottom-right-radius: 20px;
	padding: 10px;
	box-shadow: 0px 0px 20px @h_haupt_hintergrund;
}

.note-modal-content .cms_formular {
	width: 100%;
}

.note-modal-header {
	padding: 10px;
}

.note-modal-header .close {margin-top: -5px;}

.note-modal-body {
	position: relative;
	padding: 0px 10px 0px 10px;
}

.note-modal-footer {
	padding: 10px;
	text-align: center;
}

.note-modal-title {
	margin: 0px;
	font-weight: bold;
	font-size: 140%;
	color: @h_haupt_schriftfarbepositiv;
	text-align: center;
}

.note-modal-backdrop {
	position: absolute;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	z-index: 1050;
	display: none;
	background: @h_hinweis_hintergrund;
}

.note-modal-backdrop.open {display:block;}

@media(min-width:992px) {
.note-modal-content-large {width: 900px;}
}

.note-form-group {padding-bottom: 20px;}

.note-form-group:last-child {padding-bottom: 0;}

.note-form-label {
	display: block;
	margin-bottom: 10px;
	font-size: 16px;
	font-weight: bold;
	color: @h_haupt_schriftfarbepositiv;
}

.note-input {
	display: block;
	font-weight: normal;
	padding: 5px 7px;
	border-top-right-radius: 3px;
	border-top-left-radius: 3px;
	border-bottom-right-radius: 3px;
	border-bottom-left-radius: 3px;
	background: @h_formular_feldhintergrund;
	border: none;
	border-bottom: 1px solid @h_formular_feldfocushintergrund;
	width: 100%;
	transition: 500ms ease-in-out;
}

#sn-checkbox-open-in-new-window {
	width: auto;
	margin-right: 5px;
}

.note-input::-webkit-input-placeholder {color: @h_haupt_schriftfarbenegativ;}
.note-input:-moz-placeholder {color: @h_haupt_schriftfarbenegativ;}
.note-input::-moz-placeholder {color: @h_haupt_schriftfarbenegativ;}
.note-input:-ms-input-placeholder {color: @h_haupt_schriftfarbenegativ;}

.note-tooltip {
	position: absolute;
	z-index: 1070;
	display: block;
	font-size: 13px;
	opacity: 0;
}

.note-tooltip.in {opacity: .9;}

.note-tooltip.top {
	padding: 5px 0;
	margin-top: -3px;
}

.note-tooltip.right {
	padding: 0 5px;
	margin-left: 3px;
}

.note-tooltip.bottom {
	padding: 5px 0;
	margin-top: 3px;
}

.note-tooltip.left {
	padding: 0 5px;
	margin-left: -3px;
}

.note-tooltip.bottom .note-tooltip-arrow {
	top: 0;
	left: 50%;
	margin-left: -5px;
}

.note-tooltip.top .note-tooltip-arrow {
	bottom: 0;
	left: 50%;
	margin-left: -5px;
}

.note-tooltip.right .note-tooltip-arrow {
	top: 50%;
	left: 0;
	margin-top: -5px;
}

.note-tooltip.left .note-tooltip-arrow {
	top: 50%;
	right: 0;
	margin-top: -5px;
}

.note-tooltip-arrow {
	position: absolute;
	width: 0;
	height: 0;
	border-color: transparent;
	border-style: solid;
}

.note-tooltip-content {
	max-width: 200px;
	padding: 3px 8px;
	color: @h_haupt_schriftfarbenegativ;
	background: @h_hinweis_hintergrund;
	text-align: center;
	border-top-left-radius: @hinweis_radius;
	border-top-right-radius: @hinweis_radius;
	border-bottom-left-radius: @hinweis_radius;
	border-bottom-right-radius: @hinweis_radius;
}

.note-popover {
	position: absolute;
	z-index: 1060;
	display: block;
	display: none;
	font-size: 13px;
	background: @h_haupt_hintergrund;
}

.note-popover.in {display: block;}

.note-popover.top {
	padding: 5px 0;
	margin-top:-3px;
}

.note-popover.right {
	padding: 0 5px;
	margin-left: 3px;
}

.note-popover.bottom {
	padding: 5px 0;
	margin-top: 3px;
}

.note-popover.left {
	padding: 0 5px;
	margin-left: -3px;
}

.note-popover.bottom .note-popover-arrow {
	top: -11px;
	left: 50%;
	margin-left: -10px;
}

.note-popover.bottom .note-popover-arrow::after {
	top: 1px;
	margin-left: -10px;
content: ' ';
}

.note-popover.top .note-popover-arrow {
	bottom: -11px;
	left: 50%;
	margin-left: -10px;
}

.note-popover.top .note-popover-arrow::after {
	bottom: 1px;
	margin-left: -10px;
content: ' ';
}

.note-popover.right .note-popover-arrow {
	top: 50%;
	left: -11px;
	margin-top: -10px;
}

.note-popover.right .note-popover-arrow::after {
	left: 1px;
	margin-top: -10px;
content: ' ';
}

.note-popover.left .note-popover-arrow {
	top: 50%;
	right: -11px;
	margin-top: -10px;
}

.note-popover.left .note-popover-arrow::after {
	right: 1px;
	margin-top: -10px;
content:' ';
}

.note-popover-arrow {
	position: absolute;
	width: 0;
	height: 0;
}

.note-popover-arrow::after {
	position: absolute;
	display: block;
	width: 0;
	height: 0;
content: ' ';
}

.note-popover-content {
	min-width: 100px;
	min-height: 30px;
	padding: 3px 8px;
	color: @h_haupt_schriftfarbepositiv;
	text-align: center;
	background-color: @h_haupt_hintergrund;
}

.note-editor {position: relative;}

.note-editor .note-dropzone {
	position: absolute;
	z-index: 100;
	display: none;
	color: @h_haupt_schriftfarbepositiv;
	background-color: @h_haupt_meldunginfohinter;
	opacity:.95;
pointer-events:none;
}

.note-editor .note-dropzone .note-dropzone-message {
	display: table-cell;
	text-align: center;
vertical-align: middle;
}

.note-editor .note-dropzone.hover {color: @h_haupt_schriftfarbepositiv;}
.note-editor.dragover .note-dropzone {display: table;}
.note-editor .note-editing-area {position: relative;}
.note-editor .note-editing-area p {margin: 0 0 10px;}
.note-editor .note-editing-area .note-editable {outline: 0;}

.note-editor .note-editing-area .note-editable table {
	width: 100%;
	border-collapse:collapse;
}

.note-editor .note-editing-area .note-editable table td,
.note-editor .note-editing-area .note-editable table th {
	padding: 5px 3px;
	border: 1px solid @h_haupt_abstufung2;
}

.note-editor .note-editing-area .note-editable sup {vertical-align: super;}
.note-editor .note-editing-area .note-editable sub {vertical-align: sub;}
.note-editor.note-frame {border: 1px solid @h_haupt_abstufung1;}
.note-editor.note-frame.codeview .note-editing-area .note-editable {display: none;}
.note-editor.note-frame.codeview .note-editing-area .note-codable {display: block;}
.note-editor.note-frame .note-editing-area {overflow: hidden;}

.note-editor.note-frame .note-editing-area .note-editable {
	padding: 10px;
	overflow: auto;
	color: @h_haupt_schriftfarbepositiv;
word-wrap: break-word;
	background-color: @h_haupt_hintergrund;
}

.note-editor.note-frame .note-editing-area .note-editable[contenteditable="false"] {background-color: @h_haupt_abstufung1;}

.note-editor.note-frame .note-editing-area .note-codable {
	display: none;
	width: 100%;
	padding: 10px;
	margin-bottom: 0;
	font-family: monospace;
	color: @h_haupt_schriftfarbenegativ;
	background-color: @h_haupt_abstufung2;
	border: 0;
	box-sizing: border-box;
	resize: none;
}

.note-editor.note-frame.fullscreen {
	position: fixed;
	top: 0;
	left: 0;
	z-index: 1050;
	width: 100% !important;
}

.note-editor.note-frame.fullscreen .note-editable {background-color: @h_haupt_hintergrund;}
.note-editor.note-frame.fullscreen .note-resizebar {display: none;}
.note-editor.note-frame .note-statusbar {background-color: @h_haupt_abstufung1;}

.note-editor.note-frame .note-statusbar .note-resizebar {
	width: 100%;
	height: 8px;
	padding-top: 1px;
	cursor: ns-resize;
}

.note-editor.note-frame .note-statusbar .note-resizebar .note-icon-bar {
	width: 20px;
	margin: 1px auto;
	border-top: 1px solid @h_haupt_abstufung1;;
}

.note-editor.note-frame .note-placeholder {padding: 10px;}
.note-popover {
	max-width: none;
	border: 1px solid @h_haupt_abstufung1;
}

.note-popover .note-popover-content a {
	display: inline-block;
	max-width: 200px;
	overflow: hidden;
	text-overflow: ellipsis;
white-space: nowrap;
vertical-align: middle;
	margin-right: 10px;
}

.note-popover .note-popover-arrow {left: 20px !important;}

.note-popover .note-popover-content, .note-toolbar {
	padding: 0 0 5px 5px;
	margin: 0;
	background-color: @h_haupt_hintergrund;
}

.note-popover .note-popover-content > .note-btn-group, .note-toolbar > .note-btn-group {
	margin-top: 5px;
	margin-right: 5px;
	margin-left:0;
}

.note-popover .note-popover-content .note-btn-group .note-table,
.note-toolbar .note-btn-group .note-table {
	min-width: 0;
	padding: 5px;
}

.note-popover .note-popover-content .note-btn-group .note-table .note-dimension-picker,
.note-toolbar .note-btn-group .note-table .note-dimension-picker {font-size:18px;}

.note-popover .note-popover-content .note-btn-group .note-table .note-dimension-picker .note-dimension-picker-mousecatcher,
.note-toolbar .note-btn-group .note-table .note-dimension-picker .note-dimension-picker-mousecatcher {
	position: absolute !important;
	z-index: 3;
	width: 10em;
	height: 10em;
	cursor: pointer;
}

.note-popover .note-popover-content .note-btn-group .note-table .note-dimension-picker .note-dimension-picker-unhighlighted,
.note-toolbar .note-btn-group .note-table .note-dimension-picker .note-dimension-picker-unhighlighted {
	position: relative !important;
	z-index: 1;
	width: 20em;
	height: 20em;
	background-color: @h_haupt_abstufung1;
}

.note-popover .note-popover-content .note-btn-group .note-table .note-dimension-picker .note-dimension-picker-highlighted,
.note-toolbar .note-btn-group .note-table .note-dimension-picker .note-dimension-picker-highlighted {
	position: absolute !important;
	z-index: 2;
	width: 1em;
	height: 1em;
	background-color: @h_haupt_thema3;
}

.note-popover .note-popover-content .note-style h1, .note-toolbar .note-style h1,
.note-popover .note-popover-content .note-style h2, .note-toolbar .note-style h2,
.note-popover .note-popover-content .note-style h3, .note-toolbar .note-style h3,
.note-popover .note-popover-content .note-style h4, .note-toolbar .note-style h4,
.note-popover .note-popover-content .note-style h5, .note-toolbar .note-style h5,
.note-popover .note-popover-content .note-style h6, .note-toolbar .note-style h6,
.note-popover .note-popover-content .note-style blockquote, .note-toolbar .note-style blockquote {margin:0;}

.note-popover .note-popover-content .note-color .dropdown-toggle,
.note-toolbar .note-color .dropdown-toggle {
	width: 20px;
	padding-left: 5px;
}

.dropdown-toggle {
	padding-right: 2px;
}

.note-popover .note-popover-content .note-color .note-dropdown-menu,
.note-toolbar .note-color .note-dropdown-menu {min-width: 360px;}

.note-popover .note-popover-content .note-color .note-dropdown-menu .note-palette,
.note-toolbar .note-color .note-dropdown-menu .note-palette {
	display: inline-block;
	width: 160px;
	margin: 0;
}

.note-popover .note-popover-content .note-color .note-dropdown-menu .note-palette:first-child,
.note-toolbar .note-color .note-dropdown-menu .note-palette:first-child {
	margin: 0 5px;
	margin-right: 15px;
}

.note-popover .note-popover-content .note-color .note-dropdown-menu .note-palette .note-palette-title,
.note-toolbar .note-color .note-dropdown-menu .note-palette .note-palette-title {
	margin: 2px 7px;
	font-weight: bold;
	text-align: center;
}

.note-popover .note-popover-content .note-color .note-dropdown-menu .note-palette .note-color-reset,
.note-toolbar .note-color .note-dropdown-menu .note-palette .note-color-reset {
	width: 100%;
	padding: 2px 3px;
	border: 1px solid @h_haupt_hintergrund;
	margin-bottom: 3px;
	cursor: pointer;
	background-color: @h_haupt_hintergrund;
	border: 0;
}

.note-popover .note-popover-content .note-color .note-dropdown-menu .note-palette .note-color-row,
.note-toolbar .note-color .note-dropdown-menu .note-palette .note-color-row {height: 20px;}

.note-popover .note-popover-content .note-color .note-dropdown-menu .note-palette .note-color-reset:hover,
.note-toolbar .note-color .note-dropdown-menu .note-palette .note-color-reset:hover {background: #eee;}

.note-popover .note-popover-content .note-para .note-dropdown-menu,
.note-toolbar .note-para .note-dropdown-menu {
	min-width: 216px;
	padding: 5px;
}

.note-popover .note-popover-content .note-para .note-dropdown-menu > div:first-child,
.note-toolbar .note-para .note-dropdown-menu > div:first-child {margin-right: 5px;}

.note-popover .note-popover-content .note-btn-fontname .note-dropdown-menu,
.note-toolbar .note-btn-fontname .note-dropdown-menu {min-width: 200px;}

.note-popover .note-popover-content .note-dropdown-menu, .note-toolbar .note-dropdown-menu {min-width: 250px;}

.note-popover .note-popover-content .note-dropdown-menu.right, .note-toolbar .note-dropdown-menu.right {
	right: 0;
	left: auto;
}

.note-popover .note-popover-content .note-dropdown-menu.right::before, .note-toolbar .note-dropdown-menu.right::before {
	right: 9px;
	left: auto !important;
}

.note-popover .note-popover-content .note-dropdown-menu.right::after, .note-toolbar .note-dropdown-menu.right::after {
	right: 10px;
	left: auto !important;
}

.note-popover .note-popover-content .note-dropdown-menu.note-check .note-dropdown-item i,
.note-toolbar .note-dropdown-menu.note-check .note-dropdown-item i {
	color: @h_haupt_schriftfarbepositiv;
visibility: hidden;
}

.note-popover .note-popover-content .note-dropdown-menu.note-check .note-dropdown-item.checked i,
.note-toolbar .note-dropdown-menu.note-check .note-dropdown-item.checked i {visibility: visible;}

.note-popover .note-popover-content .note-dropdown-menu .note-dropdown-item > *,
.note-toolbar .note-dropdown-menu .note-dropdown-item > * {margin: 0;}

.note-popover .note-popover-content .note-fontsize-10, .note-toolbar .note-fontsize-10 {font-size: 10px;}
.note-popover .note-popover-content .note-color-palette, .note-toolbar .note-color-palette {line-height: 1;}

.note-popover .note-popover-content .note-color-palette div .note-color-btn, .note-toolbar .note-color-palette div .note-color-btn {
	width: 20px;
	height: 20px;
	padding: 0;
	margin: 0;
	border: 1px solid @h_haupt_hintergrund;
}

.note-popover .note-popover-content .note-color-palette div .note-color-btn:hover,
.note-toolbar .note-color-palette div .note-color-btn:hover {border: 1px solid @h_haupt_schriftfarbepositiv;}

.note-modal .note-modal-body label {
	display: inline-block;
	padding: 2px 5px;
	margin-bottom: 2px;
}

.note-modal .note-modal-body .help-list-item:hover {background-color: @h_haupt_abstufung1;}

@-moz-document url-prefix() {
.note-image-input {height: auto;}
}

.note-placeholder {
	position: absolute;
	display: none;
	color: @h_haupt_abstufung2;
}

.note-handle .note-control-selection {
	position: absolute;
	display: none;
	border: 1px solid @h_haupt_schriftfarbepositiv;
}

.note-handle .note-control-selection > div {position: absolute;}

.note-handle .note-control-selection .note-control-selection-bg {
	width: 100%;
	height: 100%;
	background-color: @h_haupt_schriftfarbepositiv;
	opacity:.3;
}

.note-handle .note-control-selection .note-control-handle {
	width: 7px;
	height: 7px;
	border: 1px solid @h_haupt_schriftfarbepositiv;
}

.note-handle .note-control-selection .note-control-holder {
	width: 7px;
	height: 7px;
	border: 1px solid @h_haupt_schriftfarbepositiv;
}

.note-handle .note-control-selection .note-control-sizing {
	width: 7px;
	height: 7px;
	background-color: @h_haupt_hintergrund;
	border: 1px solid @h_haupt_schriftfarbepositiv;
}

.note-handle .note-control-selection .note-control-nw {
	top: -5px;
	left: -5px;
	border-right: 0;
	border-bottom: 0;
}

.note-handle .note-control-selection .note-control-ne {
	top: -5px;
	right: -5px;
	border-bottom: 0;
	border-left: none;
}

.note-handle .note-control-selection .note-control-sw {
	bottom: -5px;
	left: -5px;
	border-top: 0;
	border-right: 0;
}

.note-handle .note-control-selection .note-control-se {
	right: -5px;
	bottom: -5px;
	cursor: se-resize;
}

.note-handle .note-control-selection .note-control-se.note-control-holder {
	cursor: default;
	border-top: 0;
	border-left: none;
}

.note-handle .note-control-selection .note-control-selection-info {
	right: 0;
	bottom: 0;
	padding: 5px;
	margin: 5px;
	font-size: 12px;
	color: @h_haupt_schriftfarbenegativ;
	background-color: @h_haupt_schriftfarbepositiv;
}

.note-hint-popover {
	min-width: 100px;
	padding: 2px;
}

.note-hint-popover .note-popover-content {
	max-height: 150px;
	padding: 3px;
	overflow: auto;
}

.note-hint-popover .note-popover-content .note-hint-group .note-hint-item {
	display: block !important;
	padding: 3px;
}

.note-hint-popover .note-popover-content .note-hint-group .note-hint-item.active,
.note-hint-popover .note-popover-content .note-hint-group .note-hint-item:hover {
	display :block;
	clear: both;
	font-weight: 400;
	line-height: 1.4;
	color: white;
	text-decoration: none;
white-space: nowrap;
	cursor: pointer;
	background-color: @h_hinweis_hintergrund;
outline: 0;
}

.help-list-item label {
	display: inline-block;
	margin-bottom: 5px;
}

.note-float-left {
	margin-right: 10px;
}

// DUNKEL;

.summernote-ui * {
	color: @d_haupt_schriftfarbepositiv;
}

.note-toolbar {
	border-bottom: 1px solid @d_haupt_abstufung1;
}

.note-btn-group {
	border: 1px solid @d_haupt_abstufung1;
}

.note-btn-group > .note-btn.focus, .note-btn-group > .note-btn-group.focus,
.note-btn-group > .note-btn.active, .note-btn-group > .note-btn-group.active {background-color: @d_haupt_abstufung1;}

.note-btn {
	color: @d_haupt_schriftfarbepositiv;
	background-color: @d_haupt_hintergrund;
}

.note-btn:focus, .note-btn.focus, .note-btn:hover {
	color: @d_haupt_schriftfarbepositiv;
	background-color: @d_haupt_abstufung1;
	border-color: @d_haupt_abstufung1;
}

.note-btn.disabled:focus, .note-btn[disabled]:focus, fieldset[disabled] .note-btn:focus, .note-btn.disabled.focus, .note-btn[disabled].focus, fieldset[disabled] .note-btn.focus {
	background-color: @d_haupt_hintergrund;
	border-color: @d_haupt_hintergrund;
}

.note-btn:hover, .note-btn:focus, .note-btn.focus {
	color: @d_haupt_schriftfarbepositiv;
	background-color: @d_haupt_abstufung1;
}

.note-btn-primary {
	background-color: @d_haupt_meldungerfolghinter;
	color: @d_button_schrift;
}

.note-btn-primary:hover, .note-btn-primary:focus, .note-btn-primary.focus {
	background-color: @d_haupt_meldungerfolgakzent;
	color: @d_button_schrifthover;
}

button.close {
	background-color: @d_haupt_meldungfehlerhinter;
	color: @d_button_schrift;
}

button.close:hover {
	background-color: @d_haupt_meldungfehlerakzent;
	color: @d_button_schrifthover;
}

.note-dropdown-menu {
	background: @d_haupt_hintergrund;
	border: 1px solid @d_haupt_abstufung1;
}

.note-dropdown-item:hover {
	background-color: @d_haupt_abstufung1;
}

a.note-dropdown-item {
	color: @d_haupt_schriftfarbepositiv;
}

.note-modal-content {
	background: @d_haupt_hintergrund;
	box-shadow: 0px 0px 20px @d_haupt_hintergrund;
}

.note-modal-title {
	color: @d_haupt_schriftfarbepositiv;
}

.note-modal-backdrop {
	background: @d_hinweis_hintergrund;
}

.note-form-label {
	color: @d_haupt_schriftfarbepositiv;
}

.note-input {
	background: @d_formular_feldhintergrund;
	border-bottom: 1px solid @d_formular_feldfocushintergrund;
}

.note-input::-webkit-input-placeholder {color: @d_haupt_schriftfarbenegativ;}
.note-input:-moz-placeholder {color: @d_haupt_schriftfarbenegativ;}
.note-input::-moz-placeholder {color: @d_haupt_schriftfarbenegativ;}
.note-input:-ms-input-placeholder {color: @d_haupt_schriftfarbenegativ;}

.note-tooltip-content {
	color: @d_haupt_schriftfarbenegativ;
	background: @d_hinweis_hintergrund;
}

.note-popover {
	background: @d_haupt_hintergrund;
}

.note-popover-content {
	color: @d_haupt_schriftfarbepositiv;
	background-color: @d_haupt_hintergrund;
}

.note-editor .note-dropzone {
	color: @d_haupt_schriftfarbepositiv;
	background-color: @d_haupt_meldunginfohinter;
}

.note-editor .note-dropzone.hover {color: @d_haupt_schriftfarbepositiv;}

.note-editor .note-editing-area .note-editable table td,
.note-editor .note-editing-area .note-editable table th {
	border: 1px solid @d_haupt_abstufung2;
}

.note-editor.note-frame {border: 1px solid @d_haupt_abstufung1;}

.note-editor.note-frame .note-editing-area .note-editable {
	color: @d_haupt_schriftfarbepositiv;
	background-color: @d_haupt_hintergrund;
}

.note-editor.note-frame .note-editing-area .note-editable[contenteditable='false'] {background-color: @d_haupt_abstufung1;}

.note-editor.note-frame .note-editing-area .note-codable {
	color: @d_haupt_schriftfarbepositiv;
	background-color: @d_haupt_hintergrund;
}

.note-editor.note-frame.fullscreen .note-editable {background-color: @d_haupt_hintergrund;}
.note-editor.note-frame .note-statusbar {background-color: @d_haupt_abstufung1;}

.note-editor.note-frame .note-statusbar .note-resizebar .note-icon-bar {
	border-top: 1px solid @d_haupt_abstufung1;;
}

.note-editor.note-frame .note-placeholder {padding: 10px;}
.note-popover {
	border: 1px solid @d_haupt_abstufung1;
}

.note-popover .note-popover-content, .note-toolbar {
	background-color: @d_haupt_hintergrund;
}

.note-popover .note-popover-content .note-btn-group .note-table .note-dimension-picker .note-dimension-picker-unhighlighted,
.note-toolbar .note-btn-group .note-table .note-dimension-picker .note-dimension-picker-unhighlighted {
	background-color: @d_haupt_abstufung1;
}

.note-popover .note-popover-content .note-btn-group .note-table .note-dimension-picker .note-dimension-picker-highlighted,
.note-toolbar .note-btn-group .note-table .note-dimension-picker .note-dimension-picker-highlighted {
	background-color: @d_haupt_thema2;
}

.note-popover .note-popover-content .note-color .note-dropdown-menu .note-palette .note-color-reset,
.note-toolbar .note-color .note-dropdown-menu .note-palette .note-color-reset {
	border: 1px solid @d_haupt_hintergrund;
	background-color: @d_haupt_hintergrund;
}

.note-popover .note-popover-content .note-dropdown-menu.note-check .note-dropdown-item i,
.note-toolbar .note-dropdown-menu.note-check .note-dropdown-item i {
	color: @d_haupt_schriftfarbepositiv;
}

.note-popover .note-popover-content .note-color-palette div .note-color-btn, .note-toolbar .note-color-palette div .note-color-btn {
	border: 1px solid @d_haupt_hintergrund;
}

.note-popover .note-popover-content .note-color-palette div .note-color-btn:hover,
.note-toolbar .note-color-palette div .note-color-btn:hover {border: 1px solid @d_haupt_schriftfarbepositiv;}

.note-modal .note-modal-body .help-list-item:hover {background-color: @d_haupt_abstufung1;}

.note-placeholder {
	color: @d_haupt_abstufung2;
}

.note-handle .note-control-selection {
	border: 1px solid @d_haupt_schriftfarbepositiv;
}

.note-handle .note-control-selection .note-control-selection-bg {
	background-color: @d_haupt_schriftfarbepositiv;
}

.note-handle .note-control-selection .note-control-handle {
	border: 1px solid @d_haupt_schriftfarbepositiv;
}

.note-handle .note-control-selection .note-control-holder {
	border: 1px solid @d_haupt_schriftfarbepositiv;
}

.note-handle .note-control-selection .note-control-sizing {
	background-color: @d_haupt_hintergrund;
	border: 1px solid @d_haupt_schriftfarbepositiv;
}

.note-handle .note-control-selection .note-control-selection-info {
	color: @d_haupt_schriftfarbenegativ;
	background-color: @d_haupt_schriftfarbepositiv;
}

.note-hint-popover .note-popover-content .note-hint-group .note-hint-item.active,
.note-hint-popover .note-popover-content .note-hint-group .note-hint-item:hover {
	background-color: @d_hinweis_hintergrund;
}

.note-icon-font {
	color: black;
}
