
let project = process.argv[2]

let projects = {
  'b': true,
  'b-mobile': true,
  'c': true,
  'c-mobile': true,
  's': true,
  's-mobile': true,
  'home': true,
  'pay': true,
  'user': true,
  'user-mobile': true,
  'headhunt-single': true,
  'hunter': true
  // '': true,
  // '': true,
}

if (!projects[project]) {
  throw new Error('wrong project name')
}

let config = {
  localPath: 'D:\\git\\tounick\\tounick-b\\src',
  remotePath: '/tmp/git/tounick-b/src',
  url: 'http://192.168.0.200:30001/sync'
}

config.localPath = `D:\\git\\tounick\\tounick-${project}\\src`
config.remotePath = `/home/dir/git-dev/tounick-${project}/src`

module.exports = config
