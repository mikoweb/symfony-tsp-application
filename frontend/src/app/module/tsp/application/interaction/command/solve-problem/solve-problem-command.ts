import Command from '@app/core/application/command-bus/command';
import { SolveRequestDto } from '@app/module/tsp/ui/dto/solve-request-dto';

export class SolveProblemCommand extends Command {
  constructor(
    public readonly request: SolveRequestDto
  ) {
    super();
  }
}
