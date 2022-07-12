<?php


namespace App\Pages\Controllers;

use App\Config;
use App\Domain\Service\Repositories\ServicesRepository;
use App\Domain\Subscription\Models\SubscriptionModel;
use App\Domain\Subscription\Repositories\SubscriptionRepository;
use App\Domain\Subscription\Schemas\SubscriptionSchema;
use App\Events;
use App\Messages;
use App\Profiler;
use App\Request;
use App\Responders\JsonResponder;
use App\Response;
use System\Pagination\PaginationSchema;
use System\Validation\ValidationFailed;


class SubscriptionController {
    /**
     * @param Config $config
     * @param Profiler $profiler
     * @param JsonResponder $responder
     * @param Messages $messages
     * @param SubscriptionRepository $subscriptionRepository
     * @param SubscriptionModel $subscriptionModel
     * @param ServicesRepository $servicesRepository
     * @param Events $events
     */
    function __construct(
        protected Config $config,
        protected Profiler $profiler,
        protected JsonResponder $responder,
        protected Messages $messages,
        protected SubscriptionRepository $subscriptionRepository,
        protected SubscriptionModel $subscriptionModel,
        protected ServicesRepository $servicesRepository,
        protected Events $events,
    ) {
    }


    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
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

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
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

        try {
            $this->subscriptionModel->create($msisdn, $service_id, function ($values, $validator) use ($service) {
                if ($values['service_id'] != $service->id) {
                    $validator->add("service_id", "Not a valid service id");
                }
            });
        } catch (ValidationFailed $validation) {
            $this->messages->error("Validation failed");
            return $this->responder->json(
                response: $response,
                data: $data,
                errors: $validation->getErrors(),
            );
        }
        $subscription = $this->subscriptionRepository->get($service_id, $msisdn);
        $subscription->charge();
        $data['subscription'] = $subscription->toSchema(new SubscriptionSchema());


        return $this->responder->json(
            response: $response,
            data: $data,
            errors: $errors,
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
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
        $subscription->charge();
        $data['subscription'] = $subscription->toSchema(new SubscriptionSchema());

        return $this->responder->json(
            response: $response,
            data: $data,
            errors: $errors,
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
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
        $subscription->cancel();
        $data['subscription'] = $subscription->toSchema(new SubscriptionSchema());

        return $this->responder->json(
            response: $response,
            data: $data,
            errors: $errors,
        );
    }


}

