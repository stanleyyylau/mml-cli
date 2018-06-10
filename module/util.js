var path = require('path')
var fs = require('fs')
var generator = require('generate-password');

var util = {};

util.generateRanomPasword = function () {
	var password = generator.generate({
	    length: 16,
	    numbers: true
	});
	return password;
}

util.readObjFromDb = function () {
	return JSON.parse(fs.readFileSync(path.join(__dirname,'..', 'database.json'), 'utf8'));
}

util.writeObjToDb = function (obj) {
	fs.writeFileSync(path.join(__dirname,'..', 'database.json'), JSON.stringify(obj, null, 4));
}

util.checkExistOfWpConfigFile = function () {
	var currentWorkingDir = process.cwd()
	var wpConfigPath = path.join(currentWorkingDir, 'wp-config.php')

	if (fs.existsSync(wpConfigPath)) {
	    return true
	} else {
		return false
	}
}

util.getThemePath = function() {
	var currentWorkingDir = process.cwd()
	return path.join(currentWorkingDir, 'wp-content', 'themes')
}

util.report = function (obj) {
	console.log('##########################')

	for (key in obj) {
		console.log(`${key}: ${obj[key]}`)
	}

	console.log('##########################')
}

module.exports = util;