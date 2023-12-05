<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class permisosMiddleware{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        $existingContent = (string) $response->getBody();
    
        $response = new Response();
        $response->getBody()->write('BEFORE' . $existingContent);
    
        return $response;
    }

    public static function verificarRolSocio(Request $request, RequestHandler $handler)
    {
        $jwtHeader = $request->getHeaderLine('Authorization');
        $tokenWithoutBearer = str_replace('Bearer ', '', $jwtHeader);
        $usuario = AutentificadorJWT::ObtenerData($tokenWithoutBearer);

        if(strtoupper($usuario->rol) === 'SOCIO'){
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'No sos Socio'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function verificarRolSocioMozo(Request $request, RequestHandler $handler)
    {
        $jwtHeader = $request->getHeaderLine('Authorization');
        $tokenWithoutBearer = str_replace('Bearer ', '', $jwtHeader);
        $usuario = AutentificadorJWT::ObtenerData($tokenWithoutBearer);

        if(strtoupper($usuario->rol) === 'MOZO' || strtoupper($usuario->rol) === 'SOCIO'){
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Accion permitida solo para socios/mozos'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

}
