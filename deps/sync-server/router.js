
let fs = require('fs')
let path = require('path')
let express = require('express')
let shelljs = require('shelljs')
let router = new express.Router()

router.post('/sync', (req, res, next) => {
  let file = req.body.file
  let content = req.body.content || ''
  console.log(new Date())
  // console.log(file)
  if (!file) {
    next(new Error(`file is not specified (${req.originalUrl})`))
  } else {
    file = path.resolve(file.replace(/\\/g, '/'))
    // console.log(`${file} ${content}`)
    console.log(`${file}`)
    // fs.mkdirSync(path.dirname(file))
    shelljs.exec(`mkdir -p ${path.dirname(file)}`)
    fs.writeFile(path.resolve(file), content || '', (err) => {
      if (err) {
        next(err)
      } else {
        res.send('OK')
      }
    })
  }
})

router.get('/', (req, res) => {
  res.send('首页')
})

module.exports = router
