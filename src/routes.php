<?php
// Routes

$app->get('/[{name}]', function ($request, $response, $args) {
    // Render index view
    return $this->response->withJson(["data" => []]);
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

$app->group('/v1', function() {
	$this->get('/search/{query:.+}', function($request, $response, $args) {
		// Simple dummy response
		$result = [
		    [
                'id' => 1,
                'title' => "L'île d'Osaka",
                'excerpt' => "Là où les gens vivent plus de 100 ans.",
                'body' => "",
                'published_date' => (new \DateTime())->format('Y-m-d H:i:s'),
                'reading_time' => 15,
                'tags' => [
                    ['id' => 1, 'title' => 'voyage'], ['id' => 2, 'title' => 'Okinawa']
                ],
                'media' => [
                    'id' => 1,
                    'original_width' => 625,
                    'original_height' => 421,
                    'path' => 'https://c1.staticflickr.com/5/4207/34223002704_4a7a3d2d86_z.jpg',
                    'background_color' => '#02b8ce'
                ]
            ]
		];

		return $response->withJson(['data' => $result ]);
	});
	$this->get('/find', function($request, $response, $args) {
		// @TODO to replace with SQL call
		$file = file_get_contents("../public/posts.minified.json");
		//$file = file_get_contents("../public/posts.json");
		$result = json_decode($file);

		$order = $request->getQueryParam('order', 'latest');
		$limit = $request->getQueryParam('limit', 10);
		$offset = $request->getQueryParam('offset', 0);

		$res = array_slice($result, $offset, $limit);

		return $response->withJson([ 'data' => $res ]);
	});
	$this->get("/image/{imageId}", function($request, $response, $args) {
		// @TODO to replace with SQL call
		$file = file_get_contents("../public/posts.minified.json");
		//$file = file_get_contents("../public/posts.json");
		$result = json_decode($file);

		$imageId = $args['imageId'];
		$post = [];
		for($i = 0; $i < count($result); $i++) {
			if($result[$i]->id == $imageId) {
				$post = $result[$i];
				break;
			}
		}

		return $response->withJson([ 'data' => [$post] ]);
	});
});
