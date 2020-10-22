<script src="/assets/js/jquery.min.js"></script>
<script>
  var unirest = require("unirest");

var req = unirest("GET", "https://community-coinbase.p.rapidapi.com/account/balance");

req.headers({
	"x-rapidapi-host": "community-coinbase.p.rapidapi.com",
	"x-rapidapi-key": "4f65db884amsh9741c1a4f668de0p104ccajsnb3197061590c",
	"useQueryString": true
});


req.end(function (res) {
	if (res.error) throw new Error(res.error);

	console.log(res.body);
});

</script>