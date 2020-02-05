// Let data format is {key: value}
let fliter = function(components){
	let temp = []
	for (let i = 0; i < components.length; i++) {
		component = components[i]
		temp[component['key']] = component['value']
	}
	return temp
}

// select the name and mimiType of plugins
let fliterPlugins = function(plugins){
	let temp = []
	if(plugins === "not avaliable"){
		temp = ["not avaliable"]
	}
	else{
		for (let i = 0; i < plugins.length; i++) {
			plugin = plugins[i]
			temp.push({'name': plugin[0], "mimeType": plugin[2][0][0]})
		}
	}
	return temp
}

// select the name of plugins while the browser is IE
let fliterPluginsIE = function(plugins){
	let temp = []
	if(plugins === "not avaliable"){
		temp = ["not avaliable"]
	}
	else{
		/* 
			22th(不包含22th)後是plugin的mime詳細資訊
			source code 用 concat 的方式串接在後面
		*/
		for (let i = 0; i<22; i++) {
			if( plugins[i] != "error"){
				temp.push(plugins[i])
			}
		}
	}
	return temp
}

let generateFinger = function(dataSet){
	return Fingerprint2.x64hash128(
		dataSet.map(function(element) {
			console.log(element)
			return element.value
	}).join(''), 1)
}

let analysisFingerpirnt = function(handle){

	Fingerprint2.get( function (components) {
		let fliterData = []
		fliterData = fliter(components)
		let UAparser = new UAParser(fliterData['userAgent'])
		let device_os = UAparser.getOS()
		let browser = UAparser.getBrowser()
		let plugins = browser['name'] == "IE" ? fliterPluginsIE(fliterData['plugins']) : fliterPlugins(fliterData['plugins'])
		/**
		*	Generate fingerprint by hash function
		*
		*	@param str the value of the all components
		*	@param int the seed number for hash function
		*	@return str a hex number which is fingerprint
		*/

		let data = {
			fingerprintValue: generateFinger(components),
			browser_name: browser['name'],
			browser_version: browser['version'],
			os_name: device_os['name'],
			os_version: device_os['version'],
			timezone: fliterData['timezone'],
			plugins: JSON.stringify(plugins),
			language: fliterData['language']
		}
		handle(data)
	})
}

let identifyResult = function(token, str, handle){
	analysisFingerpirnt(function(data){
		data['email'] = str
		data['_token'] = token
		$.post("/saveFingerprint", data, function(result, textStatus, xhr) {
			handle(result)
		})
		.fail(function(result, textStatus, xhr){
			handle(result)
		})
	})
	
}
