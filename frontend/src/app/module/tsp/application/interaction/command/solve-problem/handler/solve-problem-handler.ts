import { Injectable } from '@angular/core';
import CommandHandler from '@app/core/application/command-bus/command-handler';
import MessageService from '@app/core/application/message/message-service';
import { SolveProblemCommand } from '@app/module/tsp/application/interaction/command/solve-problem/solve-problem-command';
import { TspClient } from '@app/module/tsp/infrastructure/client/tsp-client';
import { SolutionResultStore } from '@app/module/tsp/domain/store/solution-result-store';
import { Distance } from '@app/module/tsp/domain/vo/distance';
import { Location } from '@app/module/tsp/domain/vo/location';
import { Section } from '@app/module/tsp/domain/vo/section';

@Injectable({
  providedIn: 'root',
})
export class SolveProblemHandler implements CommandHandler<SolveProblemCommand> {
  public readonly commandType: string = SolveProblemCommand.commandName;

  constructor(
    private readonly client: TspClient,
    private readonly messageService: MessageService,
    private readonly solutionResultStore: SolutionResultStore,
  ) {}

  public async execute(command: SolveProblemCommand): Promise<void> {
    let response: any;

    try {
      response = await this.client.method.post('/tsp/solve', command.request);
    } catch (error: any) {
      const message = await this.client.getValidationError(error.response);
      await this.messageService.createError({message});
    }

    if (response) {
      this.putLength(response.data);
      this.putPath(response.data);
      this.generateSections(response.data);

      console.log(this.solutionResultStore);
    }
  }

  private putLength(data: any): void {
    this.solutionResultStore.length = new Distance(data.length.value, data.length.symbol);
  }

  private putPath(data: any): void {
    if (Array.isArray(data.path)) {
      this.solutionResultStore.path = data.path.map((location: any) => {
        return new Location(location.id, location.lat, location.lng, location.name);
      });
    }
  }

  private generateSections(data: any): void {
    const sections: Section[] = [];

    for (let i = 0; i < this.solutionResultStore.path.length - 1; i++) {
      const a: Location = this.solutionResultStore.path[i];
      const b: Location = this.solutionResultStore.path[i + 1];
      const distance = data.distanceMap[a.id][b.id];

      sections.push(new Section(a, b, new Distance(distance.value, distance.symbol)));
    }

    this.solutionResultStore.sections = sections;
  }
}
