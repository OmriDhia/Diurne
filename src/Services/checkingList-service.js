import axiosInstance from '../config/http';

export default {
    async getCheckingListsByOrder(orderId) {
        try {
            const res = await axiosInstance.get(`/api/checkingLists?workshopOrderId=${orderId}`);
            return res.data?.response || [];
        } catch (error) {
            console.error('Error fetching checking lists:', error);
            throw error;
        }
    },

    async createCheckingList(orderId, payload = {}) {
        try {
            const defaultPayload = {
                workshopOrderId: orderId,
                authorId: 1, // Should come from auth/user system
                date: new Date().toISOString().split('T')[0],
                dateEndProd: '',
                comment: 'New checking list'
            };
            const finalPayload = { ...defaultPayload, ...payload };

            const res = await axiosInstance.post('/api/checkingLists', finalPayload);
            return res.data?.response;
        } catch (error) {
            console.error('Error creating checking list:', error);
            throw error;
        }
    },

    async getCheckingListById(id) {
        try {
            const res = await axiosInstance.get(`/api/checkingLists/${id}`);
            return res.data?.response || {};
        } catch (error) {
            console.error('Error fetching checking list:', error);
            throw error;
        }
    },

    async updateCheckingList(id, payload) {
        try {
            const res = await axiosInstance.put(`/api/checkingLists/${id}`, payload);
            return res.data?.data;
        } catch (error) {
            console.error('Error updating checking list:', error);
            throw error;
        }
    },

    async updateShapeValidation(id, payload) {
        try {
            // Transform the enhanced validation structure to match backend expectations
            const transformedPayload = {};
            
            // Transform shape validation
            if (payload.shapeValidation && typeof payload.shapeValidation === 'object') {
                if (payload.shapeValidation.relevant !== null) {
                    transformedPayload.shape_relevant = payload.shapeValidation.relevant;
                    
                    // When relevant is true, send validation and seen fields
                    if (payload.shapeValidation.relevant === true) {
                        transformedPayload.shape_validation = payload.shapeValidation.validation;
                        transformedPayload.shape_seen = payload.shapeValidation.seen;
                        // Always send comment when relevant is true, even if empty
                        transformedPayload.comment = payload.shapeValidation.comment || '';
                    } else {
                        // When relevant is false, reset validation and seen to null
                        transformedPayload.shape_validation = null;
                        transformedPayload.shape_seen = null;
                        transformedPayload.comment = '';
                    }
                }
            }

            // Add other shape fields
            if (payload.realWidth) transformedPayload.real_width = payload.realWidth;
            if (payload.realLength) transformedPayload.real_length = payload.realLength;
            if (payload.surface) transformedPayload.surface = payload.surface;
            if (payload.diagonalA) transformedPayload.diagonal_a = payload.diagonalA;
            if (payload.diagonalB) transformedPayload.diagonal_b = payload.diagonalB;

            const res = await axiosInstance.put(`/api/shapeValidations/${id}`, transformedPayload);
            return res.data?.data;
        } catch (error) {
            console.error('Error updating shape validation:', error);
            throw error;
        }
    },

    async updateQualityCheck(id, payload) {
        try {
            // Transform the enhanced validation structure to match backend expectations
            const transformedPayload = {};
            
            // Helper function to transform validation objects
            const transformValidationField = (fieldName, validationObj, customValidationKey = null) => {
                if (validationObj && typeof validationObj === 'object') {
                    // If relevant is set (true or false), send all validation fields
                    // This ensures the backend gets the complete validation state
                    if (validationObj.relevant !== null) {
                        transformedPayload[`${fieldName}_relevant`] = validationObj.relevant;

                        // When relevant is true, send validation and seen fields
                        const validationKey = customValidationKey || `${fieldName}_validation`;
                        if (validationObj.relevant === true) {
                            transformedPayload[validationKey] = validationObj.validation;
                            transformedPayload[`${fieldName}_seen`] = validationObj.seen;
                            // Always send comment when relevant is true, even if empty
                            transformedPayload[`${fieldName}_comment`] = validationObj.comment || '';
                        } else {
                            // When relevant is false, reset validation and seen to null
                            transformedPayload[validationKey] = null;
                            transformedPayload[`${fieldName}_seen`] = null;
                            transformedPayload[`${fieldName}_comment`] = '';
                        }
                    }
                }
            };

            // Transform all validation fields
            transformValidationField('graphic', payload.graphicValidation);
            transformValidationField('instruction', payload.instructionRespect, 'instruction_compliance_validation');
            transformValidationField('repair', payload.repairValidation, 'repair_relevant_validation');
            transformValidationField('tightness', payload.tighteningValidation);
            transformValidationField('wool', payload.woolQuality, 'wool_quality_validation');
            transformValidationField('silk', payload.silkQuality, 'silk_quality_validation');
            transformValidationField('special_shape', payload.specialShape, 'special_shape_relevant_validation');
            transformValidationField('corps_ondu_coins', payload.bodyWaveCorners);
            transformValidationField('velour_author', payload.velourAuthorValidation);
            transformValidationField('washing', payload.washingValidation);
            transformValidationField('cleaning', payload.cleaningValidation);
            transformValidationField('carving', payload.carvingValidation);
            transformValidationField('fabric_color', payload.tissueColorValidation);
            transformValidationField('frange', payload.fringeRepairValidation);
            transformValidationField('no_binding', payload.nonBindingValidation);
            transformValidationField('signature', payload.signatureValidation);
            transformValidationField('without_backing', payload.sansBackingValidation);

            // Add global comment if present
            if (payload.comment) {
                transformedPayload.comment = payload.comment;
            }

            const res = await axiosInstance.put(`/api/qualityChecks/${id}`, transformedPayload);
            return res.data?.data;
        } catch (error) {
            console.error('Error updating quality check:', error);
            throw error;
        }
    },

    async updateQualityRespect(id, payload) {
        try {
            // Transform the enhanced validation structure to match backend expectations
            const transformedPayload = {};
            
            // Helper function to transform validation objects
            const transformValidationField = (fieldName, validationObj) => {
                if (validationObj && typeof validationObj === 'object') {
                    // If relevant is set (true or false), send all validation fields
                    // This ensures the backend gets the complete validation state
                    if (validationObj.relevant !== null) {
                        transformedPayload[`${fieldName}_relevant`] = validationObj.relevant;
                        
                        // When relevant is true, send validation and seen fields
                        if (validationObj.relevant === true) {
                            transformedPayload[`${fieldName}_valid`] = validationObj.validation;
                            transformedPayload[`${fieldName}_seen`] = validationObj.seen;
                            // Always send comment when relevant is true, even if empty
                            transformedPayload[`${fieldName}_comment`] = validationObj.comment || '';
                        } else {
                            // When relevant is false, reset validation and seen to null
                            transformedPayload[`${fieldName}_valid`] = null;
                            transformedPayload[`${fieldName}_seen`] = null;
                            transformedPayload[`${fieldName}_comment`] = '';
                        }
                    }
                }
            };

            // Transform all validation fields
            transformValidationField('respect_plan', payload.respectPlanValidation);
            transformValidationField('respect_door_height', payload.respectHeightValidation);
            transformValidationField('respect_foss', payload.respectPitValidation);
            transformValidationField('respect_other_carpet', payload.respectOtherCarpetValidation);
            transformValidationField('respect_max_min_length', payload.respectLengthValidation);
            transformValidationField('respect_max_min_width', payload.respectWidthValidation);
            transformValidationField('wall_distance_top', payload.distanceTopValidation);
            transformValidationField('wall_distance_bottom', payload.distanceBottomValidation);
            transformValidationField('respectwall_distance_right', payload.distanceRightValidation);
            transformValidationField('respectwall_distance_left', payload.distanceLeftValidation);
            transformValidationField('respect_color', payload.respectColorValidation);
            transformValidationField('respect_material', payload.respectMaterialValidation);
            transformValidationField('respect_velour', payload.respectVelvetValidation);
            transformValidationField('respect_remark', payload.respectNoteValidation);

            const res = await axiosInstance.put(`/api/qualityRespects/${id}`, transformedPayload);
            return res.data?.data;
        } catch (error) {
            console.error('Error updating quality respect:', error);
            throw error;
        }
    },

    async updateLayerValidation(id, payload) {
        try {
            const res = await axiosInstance.put(`/api/layersValidations/${id}`, payload);
            return res.data?.data;
        } catch (error) {
            console.error('Error updating layer validation:', error);
            throw error;
        }
    }
};
