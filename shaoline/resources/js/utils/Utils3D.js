/**
 * Class UtilsAjax (common popin object)
 * 
 * @category   Bus
 * @package    Utils
 * @subpackage Js
 * @author     bduhot <bastien.duhot@free.fr>
 * @license    Shaoline copyright
 * @version    1.0.0
 * @link       no link
 *
 */
function Utils3D() {}


/**
 * Call controller action and do action
 * 
 * @param string sRoute  Url to call
 * @param array  aParams Post params
 * @param bool   bAsync	  False for synchrone call (default = true)
 * 
 * @return void
 */
Utils3D.createBoule = function(sDomId, sTexturePath, iWidth, iHeight, iSpeed, fRotationX, fRotationY, iX, iY){	

	if (ShaUtils3D.aParameter == undefined){
		Utils3D.aParameter = new Object();
	}
	
	if (ShaUtils3D.aParameter[sDomId] == undefined){
		Utils3D.aParameter[sDomId] = new Object();
	}
	
	Utils3D.aParameter[sDomId].mesh = undefined;
	Utils3D.aParameter[sDomId].renderer = undefined;
	Utils3D.aParameter[sDomId].scene = undefined;
	Utils3D.aParameter[sDomId].camera = undefined;
	Utils3D.aParameter[sDomId].rotationX = fRotationX;
	Utils3D.aParameter[sDomId].rotationY = fRotationY;
	

	Utils3D.aParameter[sDomId].renderer = new THREE.WebGLRenderer();


	Utils3D.aParameter[sDomId].renderer.setSize( iWidth, iHeight );
    document.getElementById(sDomId).appendChild(ShaUtils3D.aParameter[sDomId].renderer.domElement);


    Utils3D.aParameter[sDomId].scene = new THREE.Scene();

 
    Utils3D.aParameter[sDomId].camera = new THREE.PerspectiveCamera(25, iWidth / iHeight, 1, 10000 );
    Utils3D.aParameter[sDomId].camera.position.set(iX, iY, 1000);
    Utils3D.aParameter[sDomId].scene.add(ShaUtils3D.aParameter[sDomId].camera);
    

	var geometry = new THREE.SphereGeometry( 200, 32, 32 );
	var material = new THREE.MeshBasicMaterial( { map: THREE.ImageUtils.loadTexture(sTexturePath, new THREE.SphericalReflectionMapping()), overdraw: true } );
	Utils3D.aParameter[sDomId].mesh = new THREE.Mesh( geometry, material );
	Utils3D.aParameter[sDomId].scene.add( Utils3D.aParameter[sDomId].mesh );


	var lumiere = new THREE.DirectionalLight( 0xffffff, 1.0 );
	lumiere.position.set( 0, 0, 400 );
	Utils3D.aParameter[sDomId].scene.add( lumiere );
	
	setInterval(
		function (){
			Utils3D.animate(sDomId);
		},
		iSpeed
	);
	
}

Utils3D.animate = function(sDomId){
	Utils3D.aParameter[sDomId].mesh.rotation.x += Utils3D.aParameter[sDomId].rotationX;
	Utils3D.aParameter[sDomId].mesh.rotation.y += Utils3D.aParameter[sDomId].rotationY;
	Utils3D.aParameter[sDomId].renderer.render( Utils3D.aParameter[sDomId].scene, Utils3D.aParameter[sDomId].camera );
}

