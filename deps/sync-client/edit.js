
let fs = require('fs')
let path = require('path')

let project = process.argv[2]
let dir = path.join(__dirname, 'tounick-' + project)

let projects = [
  { index: 1, name: 'b' },
  { index: 2, name: 'b-mobile' },
  { index: 3, name: 's' },
  { index: 4, name: 's-mobile' },
  { index: 5, name: 'c' },
  { index: 6, name: 'c-mobile' },
  { index: 7, name: 'home' },
  { index: 8, name: 'pay' },
  { index: 9, name: 'user' },
  { index: 10, name: 'user-mobile' },
  { index: 11, name: 'headhunt-single' },
  { index: 12, name: 'hunter' }
]

let projectIndex = 0

for (let i = 0; i < projects.length; i++) {
  if (projects[i].name === project) {
    projectIndex = projects[i].index
    break
  }
}

let port1 = 21100 + projectIndex
let port2 = 21120 + projectIndex

if (!projectIndex) {
  throw new Error('wrong project name')
}

console.log(dir)

// ================================ 通用函数定义 ================================

fs.readFilePromise = (filePath, options) => {
  return new Promise((resolve, reject) => {
    fs.readFile(filePath, options, (err, data) => {
      if (err) {
        reject(err)
      } else {
        resolve(data)
      }
    })
  })
}

fs.writeFilePromise = (filePath, data, options) => {
  return new Promise((resolve, reject) => {
    fs.writeFile(filePath, data, options, err => {
      if (err) {
        reject(err)
      } else {
        resolve()
      }
    })
  })
}

// ================================  ================================
let file = path.join(dir, 'build/webpack.dev.conf.js')
console.log(file)
fs.readFilePromise(file).then(data => {
  let content = data.toString()
  content = content.replace('    new OpenBrowserPlugin', '    // new OpenBrowserPlugin')
  return fs.writeFilePromise(file, content)
}).then(() => {
  file = path.join(dir, 'config/index.js')
  return fs.readFilePromise(file)
}).then(data => {
  let content = data.toString()
  content = content.replace('port: 8080,', `port: ${port1},`)
  content = content.replace('port: 3636,', `port: ${port1},`)
  return fs.writeFilePromise(file, content)
}).then(() => {
  file = path.join(dir, 'config/proxy-config.js')
  return fs.readFilePromise(file)
}).then(data => {
  let content = data.toString()
  content = content.replace('defaultTarget: \'http://127.0.0.1:9527\',', `defaultTarget: 'http://127.0.0.1:${port2}',`)
  return fs.writeFilePromise(file, content)
}).then(() => {
  file = path.join(dir, 'rap_server/index.js')
  return fs.readFilePromise(file)
}).then(data => {
  let content = data.toString()
  content = content.replace('var server = app.listen(9527, function () {', `var server = app.listen(${port2}, function () {`)
  return fs.writeFilePromise(file, content)
}).catch(err => {
  console.log(err)
})

// ======== 暂时不管 ========

let getArgArr = (args, startIndex) => {
  if (args.length < 1) {
    return []
  }
  let arr = []
  for (let i = startIndex || 0; i < args.length; i++) {
    arr.push(args[i])
  }
  return arr
}

let promisify = (fn, thisObject, otherArgs) => {
  return new Promise((resolve, reject) => {
    let args = getArgArr(arguments, 2)
    args.push(err => {
      if (err) {
        reject(err)
      } else {
        let results = getArgArr(arguments, 1)
        if (results.length < 1) {
          resolve()
        } else if (results.length === 1) {
          resolve(results[0])
        } else {
          resolve(results)
        }
      }
    })
    fn.apply(thisObject, args)
  })
}

// console.log([1,2,3].slice(1,3))
let fn1 = (arglist, cb) => {
  console.log('fn1')
  console.log(arguments)
  cb(undefined, 'a', 0)
}

// promisify(fn1, this, 'b', 1)

/*
foo.call(this, arg1,arg2,arg3) == foo.apply(this, arguments)==this.foo(arg1, arg2, arg3)
foo.call(this, arg1,arg2,arg3) == foo.apply(this, [arg1, arg2, arg3])==this.foo(arg1, arg2, arg3)
 */
