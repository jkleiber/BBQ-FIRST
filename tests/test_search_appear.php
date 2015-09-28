<style>
* {
  box-sizing: border-box;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
}

.container {
  display: block;
  width: 25em;
  margin: 100px auto;
}

.search-container {
  overflow: hidden;
  float: right;
  height: 4em;
  width: 4em;
  border-radius: 2em;
  box-shadow: 0 0 5px #6A5D4F;
  -moz-transition: all 0.35s;
  -webkit-transition: all 0.35s;
}
.search-container:hover {
  width: 25em;
  /*border-radius: 5px 2em 2em 5px;*/
  border-radius: 2em 5px 5px 2em;
}
.search-container:hover input {
  display: inline-block;
  width: 19em;
  padding: 10px;
}

input {
  -moz-appearance: none;
  -webkit-appearance: none;
  appearance: none;
  float: right;
  width: 0em;
  height: 2em;
  margin: 1em;
  margin-right: -4.5em;
  background: #fff;
  color: #6A5D4F;
  font-size: 1em;
  font-weight: 600;
  padding: 0px;
  border: 0;
  border-radius: 5px;
  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2) inset;
  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
  -moz-transition: all 0.25s;
  -webkit-transition: all 0.25s;
}
input:focus {
  outline: none;
  box-shadow: 0 -1px 1px rgba(255, 255, 255, 0.25), 0 1px 5px rgba(0, 0, 0, 0.15);
}

.button {
  float: left;
  width: 1.75em;
  height: 1.75em;
  margin: 0.125em;
  background: #FF6100;
  text-align: center;
  font-size: 2em;
  color: #FDF6E3;
  border-radius: 50%;
  box-shadow: 0 -1px 1px rgba(255, 255, 255, 0.25), 0 1px 1px rgba(0, 0, 0, 0.25);
  text-shadow: 0 -2px 1px rgba(0, 0, 0, 0.3);
}
.button i {
  margin-top: 0.3em;
}
.button:active {
  border: 0 !important;
  text-shadow: 0 0 0;
}
</style>

<html>
<head profile="http://www.w3.org/2005/10/profile">
<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
<script src="../jquery-1.11.1.min.js"></script>
</head>
  <body>
    <div class='container'>
      <div class='search-container'>
        <input placeholder='search' type='text'>
        <a class='button'>
          <i class='icon-search'></i>
        </a>
      </div>
    </div>
  </body>
</html>