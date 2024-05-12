import { inject } from '@angular/core';
import CommandHandlerRegistry from '@app/core/application/command-bus/command-handler-registry';
import {
  SolveProblemHandler
} from '@app/module/tsp/application/interaction/command/solve-problem/handler/solve-problem-handler';

export default function tspLoader() {
  inject(CommandHandlerRegistry).registerAny([
    inject(SolveProblemHandler),
  ]);
}
