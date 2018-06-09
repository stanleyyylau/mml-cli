
let fs = require('fs')
let qs = require('querystring')
let path = require('path')
let axios = require('axios')

let config = require('./config.js')

let changed = {}

let cb = (eventType, fileName, filePath) => {
  // console.log(`${eventType} ${fileName} ${filePath}`)
  // console.log(filePath.replace(config.localPath, ''))
  if (eventType === 'change' && !changed[filePath]) {
    changed[filePath] = true
    // setTimeout(() => {
    //   changed[filePath] = false
    // }, 1000)
    fs.readFile(filePath, (err, contentBuffer) => {
      if (err) {
        console.log(err)
        return
      }
      let param = {
        file: path.join(config.remotePath, filePath.replace(config.localPath, '')),
        content: contentBuffer.toString()
      }
      console.log(param.file)
      axios.post(config.url, qs.stringify(param)).then(res => {
        console.log(res.data)
        changed[filePath] = false
      })
    })
  }
}

let watchFile = file => {
  console.log(`watch ${file}`)
  fs.watch(file, (eventType, fileName) => {
    cb(eventType, fileName, file)
  })
}

let watchDirectory = dirPath => {
  fs.watch(dirPath, (eventType, fileName) => {
    console.log(`${eventType} ${fileName}`)
  })
}

let watchFolder = folder => {
  watchDirectory(folder)
  fs.readdir(folder, (err, names) => {
    if (err) {
      console.log(err)
      return
    }
    names.forEach(name => {
      let pathName = path.join(folder, name)
      fs.stat(pathName, (err, stats) => {
        if (err) {
          console.log(err)
          return
        }
        if (stats.isFile()) {
          watchFile(pathName)
        } else if (stats.isDirectory()) {
          watchDirectory(pathName)
          watchFolder(pathName)
        }
      })
    })
  })
}

watchFolder(config.localPath)
