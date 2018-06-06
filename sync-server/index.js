
let qs = require('querystring')
let express = require('express')
let config = require('./config.js')
let router = require('./router.js')

let app = express()

app.use((req, res, next) => {
  if (req.method !== 'POST') {
    return next()
  }
  let content = ''
  req.on('data', data => {
    content += data.toString()
  })
  req.on('end', () => {
    req.rawBody = content
    req.body = qs.parse(req.rawBody)
    next()
  })
})

app.use(router)

app.use((err, req, res, next) => {
  res.send('ERROR: ' + err.message)
})

app.use((req, res, next) => {
  res.send('404')
})

let server = app.listen(config.port, () => {
  let addr = server.address()
  console.log(`listen at ${addr.address}:${addr.port}`)
})
