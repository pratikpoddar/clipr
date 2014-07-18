<style type="text/css">
/*hide unclip button if not on my own clipboard*/

.squaredFour input[type=checkbox] {
	visibility: hidden;
}

/* SQUARED FOUR */
.squaredFour {
	width: 20px;	
	margin: 2px auto;
	position: relative;
	clear: both;
	float: left;
}

.squaredFour label {
	cursor: pointer;
	position: absolute;
	width: 15px;
	height: 15px;
	top: 0;
	border-radius: 4px;

	-webkit-box-shadow: inset 0px 1px 1px white, 0px 1px 3px rgba(0,0,0,0.5);
	-moz-box-shadow: inset 0px 1px 1px white, 0px 1px 3px rgba(0,0,0,0.5);
	box-shadow: inset 0px 1px 1px white, 0px 1px 3px rgba(0,0,0,0.5);
	background: #fcfff4;

	background: -webkit-linear-gradient(top, #fcf4ff 0%, #e0efff 100%);
	background: -moz-linear-gradient(top, #fcf4ff 0%, #e0efff 100%);
	background: -o-linear-gradient(top, #fcf4ff 0%, #e0efff 100%);
	background: -ms-linear-gradient(top, #fcf4ff 0%, #e0efff 100%);
	background: linear-gradient(top, #fcf4ff 0%, #e0efff 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fcf4ff', endColorstr='#e0efff',GradientType=0 );
}

.squaredFour label:after {
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
	filter: alpha(opacity=0);
	opacity: 0;
	content: '';
	position: absolute;
	width: 9px;
	height: 5px;
	background: transparent;
	top: 3px;
	left: 2px;
	border: 3px solid #333;
	border-top: none;
	border-right: none;

	-webkit-transform: rotate(-45deg);
	-moz-transform: rotate(-45deg);
	-o-transform: rotate(-45deg);
	-ms-transform: rotate(-45deg);
	transform: rotate(-45deg);
}

.squaredFour label:hover::after {
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=30)";
	filter: alpha(opacity=30);
	opacity: 0.5;
}

.squaredFour input[type=checkbox]:checked + label:after {
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
	filter: alpha(opacity=100);
	opacity: 1;
}

#maincontent{
	opacity: 0;
}
.typeahead{
	z-index: 10001;
}
#tagsubmit{
	width:100px;
	font-size: 120%;
	line-height:30px;
	margin-top: 10px;
}
#noTagError{
	font-color:#a40;
	display: none;
}
</style>
