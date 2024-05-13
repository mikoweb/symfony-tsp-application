import { Injectable } from '@angular/core';
import { TspClient } from '@app/module/tsp/infrastructure/client/tsp-client';
import MessageService from '@app/core/application/message/message-service';
import TranslatorService from '@app/core/application/translator/TranslatorService';
import { DefaultParametersDto } from '@app/module/tsp/ui/dto/default-parameters-dto';

@Injectable({
  providedIn: 'root',
})
export class GetDefaultParametersQuery {
  constructor(
    private readonly client: TspClient,
    private readonly messageService: MessageService,
    private readonly translator: TranslatorService,
  ) {}

  public async getDefaultParameters(): Promise<DefaultParametersDto> {
    let response: any;

    try {
      response = await this.client.method.get('/tsp/default-parameters');
    } catch (error: any) {
      const message: string = await this.translator.get('tsp_problem.retrieving_default_parameters_fail');
      await this.messageService.createError({message});

      throw error;
    }

    const data: any = response.data;

    return new DefaultParametersDto(
      data.iterations,
      data.alpha,
      data.beta,
      data.distanceCoefficient,
      data.evaporation,
      data.antFactor,
      data.c,
    );
  }
}
