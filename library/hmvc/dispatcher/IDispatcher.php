<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umi\hmvc\dispatcher;

use Exception;
use umi\acl\IAclManager;
use umi\acl\IAclResource;
use umi\hmvc\component\IComponent;
use umi\hmvc\controller\IController;
use umi\hmvc\exception\RuntimeException;
use umi\hmvc\macros\IMacros;
use umi\hmvc\view\IView;
use umi\http\Request;

/**
 * Диспетчер MVC-компонентов.
 */
interface IDispatcher
{

    /**
     * Разделитель пути для вызова макроса
     */
    const MACROS_URI_SEPARATOR = '/';

    /**
     * Возвращает текущий HTTP-запрос.
     * @return Request
     */
    public function getCurrentRequest();

    /**
     * Обрабатывает http-запрос с помощью указанного MVC-компонента.
     * @param IComponent $component начальный компонент
     * @param Request $request
     */
    public function dispatchRequest(IComponent $component, Request $request);

    /**
     * Обрабатывает ошибку рендеринга.
     * @param Exception $e
     * @param IDispatchContext $failureContext контекст, в котором произошла ошибка
     * @param IController|IMacros $viewOwner
     * @return string
     */
    public function reportViewRenderError(Exception $e, IDispatchContext $failureContext, $viewOwner);

    /**
     * Обрабатывает вызов макроса.
     * @param string $macrosURI путь макроса
     * @param array $params параметры вызова макроса
     * @return string|IView
     */
    public function executeMacros($macrosURI, array $params = []);

    /**
     * Переключает обрабатываемый контекст.
     * @param IDispatchContext $context
     * @return IDispatchContext|null предыдущий обрабатываемый контескт
     */
    public function switchCurrentContext(IDispatchContext $context);

    /**
     * Возвращает текущий контекст.
     * @throws RuntimeException если контекст не был установлен
     * @return IDispatchContext
     */
    public function getCurrentContext();

    /**
     * Проверяет наличие разрешений на ресурс
     * @param IComponent $component компонент, которому принадлежит ресурс.
     * @param IAclResource|string $resource ресурс или имя ресурса
     * @param string $operationName имя операции над ресурсом
     * @return bool
     */
    public function checkPermissions(IComponent $component, $resource, $operationName = IAclManager::OPERATION_ALL);

}
 