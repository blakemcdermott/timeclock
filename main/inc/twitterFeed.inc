<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 4,
  interval: 30000,
  width: 239,
  height: 300,
  theme: {
    shell: {
      background: 'none',
      color: '#000000'
    },
    tweets: {
      background: 'none',
      color: '#414141',
      links: '#2cbcdf'
    }
  },
  features: {
    scrollbar: false,
    loop: false,
    live: false,
    behavior: 'all'
  }
}).render().setUser('TheSalesPal').start();
</script>