const path = require('path')
const fs = require('fs')
const generator = require('generate-password');

var util = {};

util.generateRanomPasword = function () {

	const password = generator.generate({
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
	const currentWorkingDir = process.cwd()
	const wpConfigPath = path.join(currentWorkingDir, 'wp-config.php')

	if (fs.existsSync(wpConfigPath)) {
	    return true
	} else {
		return false
	}
}

util.getThemePath = function() {
	const currentWorkingDir = process.cwd()
	return path.join(currentWorkingDir, 'wp-content', 'themes')
}

module.exports = util;