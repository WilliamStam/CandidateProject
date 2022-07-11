<?php


namespace App\Pages\Controllers;

use App\Config;
use App\Domain\Msisdn\Repositories\MsisdnRepository;
use App\Domain\Msisdn\Schemas\MsisdnSchema;
use App\Domain\Service\Repositories\ServicesRepository;
use App\Domain\Service\Schemas\ServiceSchema;
use App\Domain\Subscription\Repositories\SubscriptionRepository;
use App\Domain\Subscription\Schemas\SubscriptionSchema;
use App\Events;
use App\Messages;
use App\Profiler;
use App\Request;
use App\Responders\JsonResponder;
use App\Response;
use Ramsey\Uuid\Uuid;
use System\Pagination\PaginationSchema;
use System\Validation\Rules\MinLength;
use System\Validation\Rules\Required;
use System\Validation\Rules\StartsWith;
use System\Validation\Validator;


class SubscriptionController {
    function __construct(
        protected Config $config,
        protected Profiler $profiler,
        protected JsonResponder $responder,
        protected Messages $messages,
        protected SubscriptionRepository $subscriptionRepository,
        protected ServicesRepository $servicesRepository,
        protected Events $events,
    ) {
    }


    public function __invoke(Request $request, Response $response): Response {
        $errors = array();
        $data = array();

        $msisdn = $request->getAttribute("GET.msisdn");
        $service_id = $request->getAttribute("GET.service_id");
        $data['options'] = array(
            "msisdn" => $msisdn,
            "service_id" => $service_id
        );

        if ($service_id && $msisdn) {
            $data['subscription'] = $this->subscriptionRepository->get($service_id, $msisdn)
                ->toSchema(new SubscriptionSchema())
            ;

        } else {
            $data['options'] = array(
                "msisdn" => $msisdn,
                "service" => $service_id,
                "search" => $request->getAttribute("GET.search") ?? null,
                "page" => $request->getAttribute("GET.page") ?? 1,
                "paginate" => $request->getAttribute("GET.paginate") ?? 3,
                "order" => $request->getAttribute("GET.order") ?? array("id" => "desc"),
            );
            $list = $this->subscriptionRepository->list(...$data['options']);

            $data['list'] = $list->toSchema(new SubscriptionSchema());
            $data['pagination'] = $list->pagination()->toSchema(new PaginationSchema());

        }


        return $this->responder->json(
            response: $response,
            data: $data,
            errors: $errors,
        );
    }

    public function post(Request $request, Response $response): Response {
        $errors = array();
        $data = array();

        $msisdn = $request->getAttribute("GET.msisdn");
        $service_id = $request->getAttribute("GET.service_id");
        $data['options'] = array(
            "msisdn" => $msisdn,
            "service_id" => $service_id
        );


        $service = $this->servicesRepository->get($service_id);

        $errors = (new Validator(array(
            "service_id" => array(
                Required::class,
            ),
            "msisdn" => array(
                [StartsWith::class, 27],
                [MinLength::class, 11],
                Required::class,
            )
        )))->validate(array(
            "msisdn" => $msisdn,
            "service_id" => $service_id,
        ));
        if (!$service->id) {
            if (!isset($errors['service_id'])) {
                $errors['service_id'] = array();
            }
            $errors['service_id'][] = "Not a valid service id";
        }

        $subscription = $this->subscriptionRepository->get($service_id, $msisdn);

        if (!$subscription->uuid && empty($errors)) {
            $uuid = Uuid::uuid6();
            $uuid_str = $uuid->toString();
            $values = array(
                "uuid" => $uuid_str,
                "msisdn" => $msisdn,
                "service_id" => $service_id,
            );

            $errors = $subscription->validate($values);
            if (empty($errors)) {
                $subscription = $subscription->save($values);

                $this->events->emit("subscription.created", $subscription);
                $this->events->emit("subscription.charge", $subscription);
                $subscription = $this->subscriptionRepository->get($service_id, $msisdn);
            }
        }

        $data['subscription'] = $subscription->toSchema(new SubscriptionSchema());

        return $this->responder->json(
            response: $response,
            data: $data,
            errors: $errors,
        );
    }

    public function put(Request $request, Response $response): Response {
        $errors = array();
        $data = array();

        $msisdn = $request->getAttribute("GET.msisdn");
        $service_id = $request->getAttribute("GET.service_id");
        $data['options'] = array(
            "msisdn" => $msisdn,
            "service_id" => $service_id
        );

        $subscription = $this->subscriptionRepository->get($service_id, $msisdn);

        if (!$subscription->uuid) {
            if (!isset($errors['subscription'])) {
                $errors['subscription'] = array();
            }
            $errors['subscription'][] = "Subscription doesnt exist";
        }

        if (!empty($errors)) {
            return $this->responder->json(
                response: $response,
                data: $data,
                errors: $errors,
            );
        }
        $data['subscription'] = $subscription->toSchema(new SubscriptionSchema());

        $this->events->emit("subscription.charge", $subscription);

        $data['subscription'] = $subscription->toSchema(new SubscriptionSchema());

        return $this->responder->json(
            response: $response,
            data: $data,
            errors: $errors,
        );
    }

    public function delete(Request $request, Response $response): Response {
        $errors = array();
        $data = array();

        $msisdn = $request->getAttribute("GET.msisdn");
        $service_id = $request->getAttribute("GET.service_id");
        $data['options'] = array(
            "msisdn" => $msisdn,
            "service_id" => $service_id
        );

        $subscription = $this->subscriptionRepository->get($service_id, $msisdn);

        if (!$subscription->uuid) {
            if (!isset($errors['subscription'])) {
                $errors['subscription'] = array();
            }
            $errors['subscription'][] = "Subscription doesnt exist";
        }
        if (!empty($errors)) {
            return $this->responder->json(
                response: $response,
                data: $data,
                errors: $errors,
            );
        }
        $data['subscription'] = $subscription->toSchema(new SubscriptionSchema());

        $this->events->emit("subscription.cancel", $subscription);

        $data['subscription'] = $subscription->toSchema(new SubscriptionSchema());

        return $this->responder->json(
            response: $response,
            data: $data,
            errors: $errors,
        );
    }


}

