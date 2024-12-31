<?php





// namespace App\EventListener;

// use Symfony\Component\HttpKernel\Event\RequestEvent;
// use Symfony\Component\HttpFoundation\JsonResponse;

// class CORSListener
// {
//     public function onKernelRequest(RequestEvent $event)
//     {
//         $request = $event->getRequest();

//         // Intercepter uniquement les requêtes OPTIONS
//         if ($request->getMethod() === 'OPTIONS') {
//             $response = new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, [
//                 'Access-Control-Allow-Origin' => $request->headers->get('Origin', '*'),
//                 'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
//                 'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
//                 'Access-Control-Allow-Credentials' => 'true',
//             ]);
//             $event->setResponse($response);
//         }
//     }
// }namespace App\EventListener;







// namespace App\EventListener;



// use Symfony\Component\HttpKernel\Event\RequestEvent;
// use Symfony\Component\HttpFoundation\JsonResponse;

// use Symfony\Component\HttpKernel\Event\ResponseEvent;

// class CORSListener
// {
//     public function onKernelRequest(RequestEvent $event)
//     {
//         $request = $event->getRequest();

//         // Réponse pour les requêtes OPTIONS
//         if ($request->getMethod() === 'OPTIONS') {
//             $response = new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, [
//                 'Access-Control-Allow-Origin' => $request->headers->get('Origin', '*'),
//                 'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
//                 'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
//                 'Access-Control-Allow-Credentials' => 'true',
//             ]);
//             $event->setResponse($response);
//         }
//     }

//     public function onKernelResponse(ResponseEvent $event)
//     {
//         $response = $event->getResponse();
//         $request = $event->getRequest();

//         // Ajouter les en-têtes CORS à toutes les réponses
//         $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin', '*'));
//         $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
//         $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
//         $response->headers->set('Access-Control-Allow-Credentials', 'true');
//     }
// }namespace App\EventListener;







namespace App\EventListener;



use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class CORSListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        // Gérer les requêtes OPTIONS
        if ($request->getMethod() === 'OPTIONS') {
            $response = new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, [
                'Access-Control-Allow-Origin' => $request->headers->get('Origin', '*'),
                'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
                'Access-Control-Allow-Credentials' => 'true',
            ]);
            $event->setResponse($response);
        }
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        // Ajouter les en-têtes CORS à toutes les réponses
        $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin', '*'));
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
    }
}